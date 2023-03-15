<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\TransformerDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentUrgencyEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Jobs\Redis\TransferOutgoingJob;
use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantCompany;
use App\Models\Members;
use App\Models\PaymentProvider;
use App\Models\PaymentSystem;
use App\Models\PriceListFeeCurrency;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferOutgoingService extends AbstractService
{
    public function __construct(
        protected EmailService                        $emailService,
        protected AccountService                      $accountService,
        protected CommissionService                   $commissionService,
        protected TransferOutgoingRepositoryInterface $transferRepository,
        protected TransactionService                  $transactionService,
        protected CheckLimitService                   $checkLimitService
    )
    {
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

        return (float)$paymentFee;
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
                if ($args['status_id'] != PaymentStatusEnum::CANCELED->value && $args['status_id'] != PaymentStatusEnum::ERROR->value) {
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
                throw new GraphqlException('Transfer has final status which is Executed', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
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
            default:
                $this->transferRepository->update($transfer, ['status_id' => $args['status_id']]);

                break;
        }
    }

    public function updateTransferStatusToPending(TransferOutgoing $transfer): void
    {
        $this->transferRepository->update($transfer, [
            'status_id' => PaymentStatusEnum::PENDING->value,
        ]);
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

            dispatch(new TransferOutgoingJob($transfer))->afterCommit();
        });
    }


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

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::ERROR->value,
                'system_message' => $e->getMessage(),
            ]);
        }
    }

    public function updateTransferFeeAmount(TransferOutgoing $transfer, float $amount): void
    {
        DB::transaction(function () use ($transfer) {
            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::PENDING->value,
            ]);

            $this->accountService->unsetAmmountReserveOnAccountBalance($transfer);
        });

        DB::transaction(function () use ($transfer, $amount) {
            $this->transferRepository->update($transfer, [
                'amount' => $amount,
                'amount_debt' => $amount,
                'status_id' => PaymentStatusEnum::SENT->value,
            ]);

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

            dispatch(new TransferOutgoingJob($transfer))->afterCommit();
        });
    }

    public function updateTransferStatusToCancelOrError(TransferOutgoing $transfer, int $status): void
    {
        DB::transaction(function () use ($transfer, $status) {
            $this->transferRepository->update($transfer, ['status_id' => $status]);

            $this->accountService->unsetAmmountReserveOnAccountBalance($transfer);

            $this->updateApplicantBankingAccessUsedLimits($transfer);
        });
    }

    /**
     * @throws GraphqlException
     */
    public function createTransfer(array $args, int $operationType): Builder|Model
    {
        $date = Carbon::now();

        $args['user_type'] = class_basename(Members::class);
        $args['amount_debt'] = $args['amount'];
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['operation_type_id'] = $operationType;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['sender_id'] = 1;
        $args['sender_type'] = class_basename(ApplicantCompany::class);
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['reason'] = 'test';
        $args['recipient_country_id'] = 1;
        $args['respondent_fees_id'] = 2;
        $args['created_at'] = $date->format('Y-m-d H:i:s');

        if (isset($args['execution_at'])) {
            if (Carbon::parse($args['execution_at'])->lt($date)) {
                throw new GraphqlException('execution_at cannot be earlier than current date and time', 'use');
            }
        } else {
            $args['execution_at'] = $args['created_at'];
        }

        return DB::transaction(function () use ($args) {
            /** @var TransferOutgoing $transfer */
            $transfer = $this->transferRepository->createWithSwift($args);

            $transactionDTO = TransformerDTO::transform(TransactionDTO::class, $transfer, $transfer->account);
            $this->commissionService->makeFee($transfer, $transactionDTO);

            return $transfer;
        });
    }

    public function createScheduledFeeTransfer(array $args): Builder|Model
    {
        /** @var PaymentSystem $psInternal */
        $psInternal = PaymentSystem::query()->where('name', 'Internal')->first();
        /** @var PaymentProvider $ppInternal */
        $ppInternal = PaymentProvider::query()->where('name', 'Internal')->first();
        $date = Carbon::now();

        $args['requested_by_id'] = 1;
        $args['amount_debt'] = $args['amount'];
        $args['company_id'] = 1;
        $args['recipient_bank_country_id'] = 1;
        $args['group_id'] = 1;
        $args['group_type_id'] = 1;
        $args['project_id'] = 1;
        $args['price_list_id'] = 1;
        $args['price_list_fee_id'] = 1;
        $args['user_type'] = class_basename(Members::class);
        $args['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $args['urgency_id'] = 1;
        $args['operation_type_id'] = OperationTypeEnum::SCHEDULED_FEE->value;
        $args['payment_provider_id'] = $ppInternal->id;
        $args['payment_system_id'] = $psInternal->id;
        $args['payment_bank_id'] = 2;
        $args['payment_number'] = rand();
        $args['sender_id'] = 1;
        $args['sender_type'] = class_basename(ApplicantCompany::class);
        $args['system_message'] = 'test';
        $args['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $args['recipient_country_id'] = 1;
        $args['respondent_fees_id'] = 1;
        $args['created_at'] = $date->format('Y-m-d H:i:s');
        $args['execution_at'] = $args['created_at'];

        return $this->transferRepository->create($args);
    }

    public function attachFileById(TransferOutgoing $transfer, array $fileIds): void
    {
        $this->transferRepository->attachFileById($transfer, $fileIds);
    }
}
