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
use App\Models\Members;
use App\Models\TransferBetweenRelation;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransferBetweenUsersService extends AbstractService
{
    public function __construct(
        protected CommissionService $commissionService,
        protected TransferOutgoingService $transferOutgoingService,
        protected TransferIncomingService $transferIncomingService,
        protected TransferIncomingRepositoryInterface $transferIncomingRepository,
        protected TransferOutgoingRepositoryInterface $transferOutgoingRepository,
        protected TransactionService $transactionService,
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function getTransfersByIncomingId(int $id): array
    {
        $transfers['incoming'] = $this->transferIncomingRepository->findById($id);
        if (empty($transfers['incoming'])) {
            throw new GraphqlException('Transfer not found', 'not found', 404);
        }

        $transferOutgoingId = TransferBetweenRelation::where('transfer_incoming_id', $id)->first()->transfer_outgoing_id;
        $transfers['outgoing'] = $this->transferOutgoingRepository->findById($transferOutgoingId);

        return $transfers;
    }

    public function createTransfer(array $args, int $operationType): Builder|Model
    {
        $fromAccount = Account::find($args['from_account_id']);
        $toAccount = Account::find($args['to_account_id']);

        $this->validateCreateTransfer($fromAccount, $toAccount);

        $data = $this->populateTransferData($args, $fromAccount, $toAccount, $operationType);

        $transfers = DB::transaction(function () use ($data) {
            $outgoing = $this->transferOutgoingRepository->create($data['outgoing']);
            $incoming = $this->transferIncomingRepository->create($data['incoming']);

            TransferBetweenRelation::create([
                'transfer_outgoing_id' => $outgoing->id,
                'transfer_incoming_id' => $incoming->id,
            ]);

            $this->commissionService->makeFee($outgoing);

            return [
                'outgoing' => $outgoing,
                'incoming' => $incoming,
            ];
        });

        $this->attachFileById($transfers, $args['file_id'] ?? []);

        return $transfers['incoming'];
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

        $this->transferOutgoingService->updateTransferStatusToExecuted($transfers['outgoing'], $transactionOutgoing);
        $this->transferIncomingService->updateTransferStatusToExecuted($transfers['incoming'], $transactionIncoming);
    }

    private function populateTransferData(array $args, Account $fromAccount, Account $toAccount, int $operationType): array
    {
        $date = Carbon::now();

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
        $outgoing['payment_number'] = 'BTW' . rand();
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
        $incoming['amount'] = $args['amount'];
        $incoming['amount_debt'] = $args['amount'];
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
        $incoming['respondent_fees_id'] = 2;
        $incoming['group_id'] = 1;
        $incoming['group_type_id'] = 1;
        $incoming['project_id'] = 1;
        $incoming['price_list_id'] = 1;
        $incoming['price_list_fee_id'] = 121;
        $incoming['requested_by_id'] = 1;
        $incoming['created_at'] = $date->format('Y-m-d H:i:s');
        $incoming['execution_at'] = $date->format('Y-m-d H:i:s');

        return [
            'outgoing' => $outgoing,
            'incoming' => $incoming,
        ];
    }

    /**
     * @throws GraphqlException
     */
    private function validateUpdateTransferStatus(array $transfers, array $args): void
    {
        foreach ($transfers as $transfer) {
            if ($transfer->operation_type_id != OperationTypeEnum::BETWEEN_ACCOUNT->value || $transfer->operation_type_id != OperationTypeEnum::BETWEEN_USERS->value) {
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

        if ($fromAccount->currencies->id != $toAccount->currencies->id) {
            throw new GraphqlException('Account currencies are different', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
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
