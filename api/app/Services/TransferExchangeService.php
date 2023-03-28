<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\TransformerDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferChannelEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\CurrencyExchangeRate;
use App\Models\Members;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransferExchangeService extends AbstractService
{
    public function __construct(
        protected CommissionService $commissionService,
        protected TransferOutgoingService $transferOutgoingService,
        protected TransferIncomingService $transferIncomingService,
        protected TransferIncomingRepositoryInterface $transferIncomingRepository,
        protected TransferOutgoingRepositoryInterface $transferOutgoingRepository,
        protected TransferExchangeRepositoryInterface $transferExchangeRepository,
        protected TransactionService $transactionService,
    ) {
    }

    public function createTransfer(array $args, int $operationType): Builder|Model
    {
        $fromAccount = Account::find($args['from_account_id']);
        $toAccount = Account::find($args['to_account_id']);

        $this->validateCreateTransfer($fromAccount, $toAccount);

        $data = $this->populateTransferData($args, $fromAccount, $toAccount, $operationType);

        $transfers = DB::transaction(function () use ($data, $fromAccount, $toAccount) {
            $outgoing = $this->transferOutgoingRepository->create($data['outgoing']);
            $incoming = $this->transferIncomingRepository->create($data['incoming']);
            $exchange = $this->transferExchangeRepository->create([
                'company_id' => $outgoing->company_id,
                'client_id' => $outgoing->account?->company_id,
                'requested_by_id' => $outgoing->requested_by_id,
                'debited_account_id' => $outgoing->account?->id,
                'credited_account_id' => $incoming->account?->id,
                'status_id' => $outgoing->status_id,
                'transfer_outgoing_id' => $outgoing->id,
                'transfer_incoming_id' => $incoming->id,
                'exchange_rate' => $data['exchange_rate'],
            ]);

            $transactionOutgoing = TransformerDTO::transform(TransactionDTO::class, $outgoing, $fromAccount, $toAccount);
            $this->commissionService->makeFee($outgoing, $transactionOutgoing);

            return [
                'outgoing' => $outgoing, 
                'incoming' => $incoming,
                'exchange' => $exchange,
            ];
        });
        
        $this->attachFileById($transfers, $args['file_id'] ?? []);

        return $transfers['exchange'];
    }

    /**
     * @throws GraphqlException
     */
    private function getExchangeRate(Account $fromAccount, Account $toAccount): float
    {
        $exchageRate = CurrencyExchangeRate::where('currency_from_id', $fromAccount->currencies->id)
            ->where('currency_to_id', $toAccount->currencies->id)
            ->first()
            ->rate ?? null;
        
        if ($exchageRate === null) {
            throw new GraphqlException('Exchange rate not found', Response::HTTP_BAD_REQUEST);
        }

        return $exchageRate;
    }

    /**
     * @throws GraphqlException
     */
    private function getExchangeAmount(float $amount, Account $fromAccount, Account $toAccount): float
    {
        $exchageRate = $this->getExchangeRate($fromAccount, $toAccount);

        return $amount * $exchageRate;
    }

    private function populateTransferData(array $args, Account $fromAccount, Account $toAccount, int $operationType): array
    {
        $date = Carbon::now();
        $toAmmount = $this->getExchangeAmount($args['amount'], $fromAccount, $toAccount);

        // Outgoing
        $outgoing['account_id'] = $fromAccount->id;
        $outgoing['currency_id'] = $fromAccount->currencies->id;
        $outgoing['company_id'] = 1;
        $outgoing['user_type'] = class_basename(Members::class);
        $outgoing['amount'] = $args['amount'];
        $outgoing['amount_debt'] = $args['amount'];
        $outgoing['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $outgoing['urgency_id'] = 1;
        $outgoing['operation_type_id'] = $operationType;
        $outgoing['payment_bank_id'] = 2;
        $outgoing['payment_number'] = 'EXCH' . rand();
        $outgoing['payment_provider_id'] = 1;
        $outgoing['payment_system_id'] = 1;
        $outgoing['recipient_id'] = 1;
        $outgoing['recipient_type'] = class_basename(ApplicantCompany::class);
        $outgoing['system_message'] = 'test';
        $outgoing['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $outgoing['reason'] = 'test';
        $outgoing['sender_country_id'] = 1;
        $outgoing['respondent_fees_id'] = 2;
        $outgoing['group_id'] = 1;
        $outgoing['group_type_id'] = 1;
        $outgoing['project_id'] = 1;
        $outgoing['price_list_id'] = 1;
        $outgoing['price_list_fee_id'] = 121;
        $outgoing['requested_by_id'] = 1;
        $outgoing['created_at'] = $date->format('Y-m-d H:i:s');
        $outgoing['execution_at'] = $date->format('Y-m-d H:i:s');
        $outgoing['sender_id'] = 1;
        $outgoing['sender_type'] = class_basename(ApplicantCompany::class);
        $outgoing['recipient_bank_country_id'] = 1;
        $outgoing['recipient_country_id'] = 1;

        // Incoming
        $incoming['account_id'] = $toAccount->id;
        $incoming['currency_id'] = $toAccount->currencies->id;
        $incoming['company_id'] = 1;
        $incoming['user_type'] = class_basename(Members::class);
        $incoming['amount'] = $toAmmount;
        $incoming['amount_debt'] = $toAmmount;
        $incoming['status_id'] = PaymentStatusEnum::UNSIGNED->value;
        $incoming['urgency_id'] = 1;
        $incoming['operation_type_id'] = $operationType;
        $incoming['payment_bank_id'] = 2;
        $incoming['payment_number'] = $outgoing['payment_number'];
        $incoming['payment_provider_id'] = 1;
        $incoming['payment_system_id'] = 1;
        $incoming['recipient_id'] = 1;
        $incoming['recipient_type'] = class_basename(ApplicantCompany::class);
        $incoming['system_message'] = 'test';
        $incoming['channel'] = TransferChannelEnum::BACK_OFFICE->toString();
        $incoming['reason'] = 'test';
        $incoming['sender_country_id'] = 1;
        $incoming['respondent_fees_id'] = 1;
        $incoming['group_id'] = 1;
        $incoming['group_type_id'] = 1;
        $incoming['project_id'] = 1;
        $incoming['price_list_id'] = 1;
        $incoming['price_list_fee_id'] = 121;
        $incoming['requested_by_id'] = 2;
        $incoming['created_at'] = $date->format('Y-m-d H:i:s');
        $incoming['execution_at'] = $date->format('Y-m-d H:i:s');

        return [
            'outgoing' => $outgoing,
            'incoming' => $incoming,
            'exchange_rate' => $this->getExchangeRate($fromAccount, $toAccount),
        ];
    }

    /**
     * @throws GraphqlException
     */
    public function updateTransferStatus(array $transfers, array $args): void
    {
        $this->validateUpdateTransferStatus($transfers, $args);

        switch ($args['status_id']) {
            case PaymentStatusEnum::PENDING->value:
                $this->updateTransferStatusToPending($transfers);

                break;
            case PaymentStatusEnum::EXECUTED->value:
                $this->updateTransferStatusToExecuted($transfers);

                break;
        }
    }

    public function updateTransferStatusToPending(array $transfers): void
    {
        DB::transaction(function () use ($transfers) {
            $this->transferOutgoingRepository->update($transfers['outgoing'], [
                'status_id' => PaymentStatusEnum::PENDING->value,
            ]);

            $this->transferIncomingRepository->update($transfers['incoming'], [
                'status_id' => PaymentStatusEnum::PENDING->value,
            ]);
            
            $this->transferExchangeRepository->update($transfers['exchange'], [
                'status_id' => PaymentStatusEnum::PENDING->value,
            ]);
        });
    }

    /**
     * @throws EmailException
     * @throws GraphqlException
     */
    public function updateTransferStatusToExecuted(array $transfers): void
    {
        $fromAccount = Account::find($transfers['outgoing']->account_id);
        $toAccount = Account::find($transfers['incoming']->account_id);

        $transactionOutgoing = TransformerDTO::transform(TransactionDTO::class, $transfers['outgoing'], $fromAccount, $toAccount);
        $transactionIncoming = TransformerDTO::transform(TransactionDTO::class, $transfers['incoming'], $fromAccount, $toAccount);
        $this->commissionService->makeFee($transfers['outgoing'], $transactionOutgoing);

        $this->transferOutgoingService->updateTransferStatusToExecuted($transfers['outgoing'], $transactionOutgoing);
        $this->transferIncomingService->updateTransferStatusToExecuted($transfers['incoming'], $transactionIncoming);

        $this->transferExchangeRepository->update($transfers['exchange'], [
            'status_id' => PaymentStatusEnum::EXECUTED->value,
        ]);
    }

    /**
     * @throws GraphqlException
     */
    private function validateUpdateTransferStatus(array $transfers, array $args): void
    {
        unset($transfers['exchange']);

        foreach ($transfers as $transfer) {
            if ($transfer->operation_type_id != OperationTypeEnum::EXCHANGE->value) {
                throw new GraphqlException('This operation is not allowed for this transfer', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            switch ($transfer['status_id']) {
                case PaymentStatusEnum::UNSIGNED->value:
                    if ($args['status_id'] != PaymentStatusEnum::PENDING->value) {
                        throw new GraphqlException('This status is not allowed for transfer which has Unsigned status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    break;
                case PaymentStatusEnum::PENDING->value:
                    $allowedStatuses = [
                        PaymentStatusEnum::ERROR->value,
                        PaymentStatusEnum::CANCELED->value,
                        PaymentStatusEnum::EXECUTED->value,
                    ];
        
                    if (! in_array($args['status_id'], $allowedStatuses)) {
                        throw new GraphqlException('This status is not allowed for transfer which has Pending status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
        
                    break;
                case PaymentStatusEnum::EXECUTED->value:
                    throw new GraphqlException('Transfer has final status which is Executed', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        
                    break;
            }
        }
    }

    /**
     * @throws GraphqlException
     */
    private function validateCreateTransfer(Account $fromAccount, Account $toAccount): void
    {
        if (!$fromAccount) {
            throw new GraphqlException('From account not found', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$toAccount) {
            throw new GraphqlException('To account not found', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($fromAccount->owner_id != $toAccount->owner_id) {
            throw new GraphqlException('Accounts do not belong to the same owner', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($fromAccount->currencies->id == $toAccount->currencies->id) {
            throw new GraphqlException('Account currencies are the same', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function attachFileById(array $transfers, array $fileIds): void
    {
        DB::transaction(function () use ($transfers, $fileIds) {
            $this->transferOutgoingRepository->attachFileById($transfers['outgoing'], $fileIds);
            $this->transferIncomingRepository->attachFileById($transfers['incoming'], $fileIds);
        });
    }
}
