<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\Transfer\Create\Incoming\CreateTransferIncomingBetweenUsersDTO;
use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingBetweenUsersDTO;
use App\DTO\TransformerDTO;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\TransferBetweenRelation;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransferBetweenUsersService extends AbstractService
{
    public function __construct(
        protected CommissionService                   $commissionService,
        protected TransferOutgoingService             $transferOutgoingService,
        protected TransferIncomingService             $transferIncomingService,
        protected TransferIncomingRepositoryInterface $transferIncomingRepository,
        protected TransferOutgoingRepositoryInterface $transferOutgoingRepository,
        protected TransactionService                  $transactionService,
    )
    {
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

    public function createTransfer(array $args, int $operationType): array
    {
        $fromAccount = Account::find($args['from_account_id']);
        $toAccount = Account::find($args['to_account_id']);

        $this->validateCreateTransfer($fromAccount, $toAccount);

        $outgoingDTO = TransformerDTO::transform(CreateTransferOutgoingBetweenUsersDTO::class, $fromAccount, $operationType, $args);
        $incomingDTO = TransformerDTO::transform(CreateTransferIncomingBetweenUsersDTO::class, $toAccount, $operationType, $args, $outgoingDTO->payment_number, $outgoingDTO->created_at);

        $transfers = DB::transaction(function () use ($outgoingDTO, $incomingDTO) {
            /** @var TransferOutgoing $outgoing */
            $outgoing = $this->transferOutgoingRepository->create($outgoingDTO->toArray());
            /** @var TransferIncoming $outgoing */
            $incoming = $this->transferIncomingRepository->create($incomingDTO->toArray());

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

        return $transfers;
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

    public function attachFile(array $args): TransferOutgoing|TransferIncoming|Model|null
    {
        if ($args['type'] == FeeTransferTypeEnum::INCOMING->toString()) {
            $transfer = TransferIncoming::query()->findOrFail($args['transfer_id']);
            return $this->transferIncomingRepository->attachFileById($transfer, $args['file_id']);
        } else {
            $transfer = TransferOutgoing::query()->findOrFail($args['transfer_id']);
            return $this->transferOutgoingRepository->attachFileById($transfer, $args['file_id']);
        }
    }

    /**
     * @throws GraphqlException
     */
    private function validateUpdateTransferStatus(array $transfers, array $args): void
    {
        foreach ($transfers as $transfer) {
            if ($transfer->operation_type_id != (OperationTypeEnum::BETWEEN_ACCOUNT->value || OperationTypeEnum::BETWEEN_USERS->value)) {
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

                    if (!in_array($args['status_id'], $allowedStatuses)) {
                        throw new GraphqlException('This status is not allowed for transfer which has Pending status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    break;
                case PaymentStatusEnum::EXECUTED->value:
                    throw new GraphqlException('Transfer has final status which is Executed', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
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
