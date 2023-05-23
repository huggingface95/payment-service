<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\Transfer\Create\Incoming\CreateTransferIncomingRefundDTO;
use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingScheduledFeeDTO;
use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingStandardDTO;
use App\DTO\TransformerDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferHistoryActionEnum;
use App\Enums\TransferTypeEnum;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\TransferOutgoingJob;
use App\Models\ApplicantBankingAccess;
use App\Models\PriceListFeeCurrency;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Traits\TransferHistoryTrait;
use App\Traits\UpdateTransferStatusTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TransferOutgoingService extends AbstractService
{
    use TransferHistoryTrait, UpdateTransferStatusTrait;

    public function __construct(
        protected EmailService $emailService,
        protected AccountService $accountService,
        protected CommissionService $commissionService,
        protected TransferOutgoingRepositoryInterface $transferRepository,
        protected TransferIncomingService $transferIncomingService,
        protected TransactionService $transactionService,
        protected CheckLimitService $checkLimitService
    ) {
    }

    public function commissionCalculationFeeScheduled($amount, $fee, $currencyId): float
    {
        $paymentFee = 0;

        $priceListFees = PriceListFeeCurrency::query()->where('price_list_fee_id', $fee->price_list_id)
            ->where('currency_id', $currencyId)
            ->get();

        foreach ($priceListFees as $listFee) {
            $paymentFee += $this->commissionService->getFee($listFee->fee, $amount, PaymentUrgencyEnum::STANDART->value);
        }

        return (float) $paymentFee;
    }

    public function updateApplicantBankingAccessUsedLimits(TransferOutgoing $transaction): void
    {
        $usedMonthly = $this->transferRepository->getSumOfMonthlySentTransfersByApplicantIndividualId($transaction->requested_by_id);
        $usedDaily = $this->transferRepository->getSumOfDailySentTransfersByApplicantIndividualId($transaction->requested_by_id);

        $applicantBankingAccess = ApplicantBankingAccess::query()->where('applicant_individual_id', $transaction->requested_by_id)
            ->where('applicant_company_id', $transaction->sender_id)
            ->first();

        $applicantBankingAccess?->update([
            'used_monthly_limit' => $usedMonthly,
            'used_daily_limit' => $usedDaily,
        ]);
    }

    /**
     * @throws GraphqlException
     */
    public function validateUpdateTransferStatus(TransferOutgoing $transfer, array $args): void
    {
        $date = Carbon::today();

        switch ($transfer->status_id) {
            case PaymentStatusEnum::UNSIGNED->value:
                if ($args['status_id'] != PaymentStatusEnum::PENDING->value && $args['status_id'] != PaymentStatusEnum::WAITING_EXECUTION_DATE->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Unsigned status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::WAITING_EXECUTION_DATE->value:
                $executionAt = Carbon::parse($transfer->execution_at)->format('Y-m-d');
                if ($date->lt($executionAt)) {
                    if ($args['status_id'] == PaymentStatusEnum::SENT->value) {
                        throw new GraphqlException('The execution date has not yet arrived, please change the execution date', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    throw new GraphqlException('Waiting execution date', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::PENDING->value:
                if ($args['status_id'] != PaymentStatusEnum::SENT->value && $args['status_id'] != PaymentStatusEnum::CANCELED->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Pending status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::SENT->value:
                if ($args['status_id'] != PaymentStatusEnum::EXECUTED->value && $args['status_id'] != PaymentStatusEnum::ERROR->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Sent status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::ERROR->value:
                if ($args['status_id'] != PaymentStatusEnum::PENDING->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Error status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::CANCELED->value:
                throw new GraphqlException('Transfer has final status which is Canceled', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            case PaymentStatusEnum::EXECUTED->value:
                if ($args['status_id'] != PaymentStatusEnum::REFUND->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Executed status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }
        }
    }

    /**
     * @throws GraphqlException
     */
    public function updateTransferStatus(TransferOutgoing $transfer, array $args): void
    {
        $this->validateUpdateTransferStatus($transfer, $args);

        switch ($args['status_id']) {
            case PaymentStatusEnum::PENDING->value:
                $this->updateTransferStatusToPending($transfer);

                break;
            case PaymentStatusEnum::ERROR->value:
            case PaymentStatusEnum::CANCELED->value:
                $this->updateTransferStatusToCancelOrError($transfer, $args['status_id']);

                break;
            case PaymentStatusEnum::SENT->value:
                $this->updateTransferStatusToSent($transfer);

                break;
            case PaymentStatusEnum::EXECUTED->value:
                $transactionDTO = TransformerDTO::transform(TransactionDTO::class, $transfer, $transfer->account);
                $this->updateTransferStatusToExecuted($transfer, $transactionDTO);

                break;
            case PaymentStatusEnum::REFUND->value:
                $this->updateTransferStatusToCancelAndRefund($transfer);

                break;
        }
    }

    public function updateTransfer(TransferOutgoing $transfer, array $args): void
    {
        if ($transfer->status_id !== PaymentStatusEnum::UNSIGNED->value) {
            throw new GraphqlException('Transfer status is not Unsigned');
        }

        DB::transaction(function () use ($transfer, $args) {
            if ($transfer->transfer_type_id !== TransferTypeEnum::FEE->value && isset($args['amount']) && $args['amount'] != $transfer->amount) {
                $transfer->amount = $args['amount'];
                $this->commissionService->deleteFee($transfer);

                $transactionDTO = TransformerDTO::transform(TransactionDTO::class, $transfer, $transfer->account);
                $this->commissionService->makeFee($transfer, $transactionDTO);

                $this->createPPHistory($transfer);
            }

            $this->transferRepository->update($transfer, $args);

            $this->createTransferHistory($transfer);
        });
    }

    public function updateTransferStatusToSent(TransferOutgoing $transfer): void
    {
        DB::transaction(function () use ($transfer) {
            $this->checkLimitService->checkApplicantBankingAccessUsedLimits($transfer);

            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::SENT->value,
            ]);

            $this->accountService->setAmmountReserveOnAccountBalance($transfer);

            $this->updateApplicantBankingAccessUsedLimits($transfer);

            $this->createTransferHistory($transfer);

            dispatch(new TransferOutgoingJob($transfer))->afterCommit();
        });
    }

    /**
     * @throws GraphqlException
     */
    public function updateTransferStatusToExecuted(TransferOutgoing $transfer, TransactionDTO $transactionDTO = null): void
    {
        DB::beginTransaction();

        try {
            $this->checkLimitService->checkApplicantBankingAccessUsedLimits($transfer);

            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::EXECUTED->value,
            ]);

            if ($transactionDTO) {
                $this->transactionService->createTransaction($transactionDTO);
            }

            $this->accountService->withdrawFromBalance($transfer);

            $this->updateApplicantBankingAccessUsedLimits($transfer);

            $this->createTransferHistory($transfer);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::ERROR->value,
                'system_message' => $e->getMessage(),
            ]);

            throw new GraphqlException($e->getMessage(), 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function updateTransferFeeAmount(TransferOutgoing $transfer, float $amount): void
    {
        DB::transaction(function () use ($transfer) {
            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::PENDING->value,
            ]);

            $this->createTransferHistory($transfer);

            $this->accountService->unsetAmmountReserveOnAccountBalance($transfer);
        });

        DB::transaction(function () use ($transfer, $amount) {
            $this->transferRepository->update($transfer, [
                'amount' => $amount,
                'amount_debt' => $amount,
                'status_id' => PaymentStatusEnum::SENT->value,
            ]);

            $this->createTransferHistory($transfer);

            $this->accountService->setAmmountReserveOnAccountBalance($transfer);
        });
    }

    public function updateTransferFeeStatusToSent(TransferOutgoing $transfer): void
    {
        DB::transaction(function () use ($transfer) {
            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::SENT->value,
            ]);

            $this->accountService->setAmmountReserveOnAccountBalance($transfer);

            $this->createTransferHistory($transfer);

            dispatch(new TransferOutgoingJob($transfer))->afterCommit();
        });
    }

    public function updateTransferStatusToCancelOrError(TransferOutgoing $transfer, int $status): void
    {
        DB::transaction(function () use ($transfer, $status) {
            $this->transferRepository->update($transfer, ['status_id' => $status]);

            $this->accountService->unsetAmmountReserveOnAccountBalance($transfer);

            $this->updateApplicantBankingAccessUsedLimits($transfer);

            $this->createTransferHistory($transfer);
        });
    }

    private function updateTransferStatusToCancelAndRefund(TransferOutgoing $transfer): void
    {
        DB::transaction(function () use ($transfer) {
            $this->transferRepository->update($transfer, ['status_id' => PaymentStatusEnum::CANCELED->value]);

            $this->createTransferHistory($transfer);

            // Create incoming transfer
            $transferDto = TransformerDTO::transform(CreateTransferIncomingRefundDTO::class, $transfer->toArray(), OperationTypeEnum::INCOMING_WIRE_TRANSFER->value);
            $this->transferIncomingService->createTransfer($transferDto->toArray(), OperationTypeEnum::INCOMING_WIRE_TRANSFER->value);
        });
    }

    public function createTransfer(array $args, int $operationType): Builder|Model
    {
        $createTransferDto = TransformerDTO::transform(CreateTransferOutgoingStandardDTO::class, $args, $operationType, $this->transferRepository);

        return DB::transaction(function () use ($createTransferDto) {
            /** @var TransferOutgoing $transfer */
            $transfer = $this->transferRepository->createWithSwift($createTransferDto->toArray());

            $transactionDTO = TransformerDTO::transform(TransactionDTO::class, $transfer, $transfer->account);
            $this->commissionService->makeFee($transfer, $transactionDTO);

            $this->createTransferHistory($transfer, TransferHistoryActionEnum::INIT->value)->createPPHistory($transfer);

            return $transfer;
        });
    }

    public function createScheduledFeeTransfer(array $args): Builder|Model
    {
        $createTransferDto = TransformerDTO::transform(CreateTransferOutgoingScheduledFeeDTO::class, $args);

        return $this->transferRepository->create($createTransferDto->toArray());
    }

    public function attachFileById(TransferOutgoing $transfer, array $fileIds): void
    {
        $this->transferRepository->attachFileById($transfer, $fileIds);
    }
}
