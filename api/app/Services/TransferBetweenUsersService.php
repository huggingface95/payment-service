<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\Transfer\Create\Incoming\CreateTransferIncomingBetweenUsersDTO;
use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingBetweenUsersDTO;
use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingRefundDTO;
use App\DTO\TransformerDTO;
use App\Enums\FeeTransferTypeEnum;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferHistoryActionEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\TransferBetween;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Traits\TransferHistoryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransferBetweenUsersService extends AbstractService
{
    use TransferHistoryTrait;

    public function __construct(
        protected CommissionService $commissionService,
        protected TransferOutgoingService $transferOutgoingService,
        protected TransferIncomingService $transferIncomingService,
        protected TransferIncomingRepositoryInterface $transferIncomingRepository,
        protected TransferOutgoingRepositoryInterface $transferOutgoingRepository,
        protected TransactionService $transactionService,
    ) {
    }

    public function createTransfer(array $args, int $operationType): TransferBetween
    {
        $fromAccount = Account::findOrFail($args['from_account_id']);
        $toAccount = Account::findOrFail($args['to_account_id']);

        $this->validateCreateTransfer($fromAccount, $toAccount, $operationType);

        $outgoingDTO = TransformerDTO::transform(CreateTransferOutgoingBetweenUsersDTO::class, $fromAccount, $toAccount, $operationType, $args);
        $incomingDTO = TransformerDTO::transform(CreateTransferIncomingBetweenUsersDTO::class, $toAccount, $fromAccount, $operationType, $args, $outgoingDTO->payment_number, $outgoingDTO->created_at);

        $transfers = DB::transaction(function () use ($outgoingDTO, $incomingDTO) {
            /** @var TransferOutgoing $outgoing */
            $outgoing = $this->transferOutgoingRepository->create($outgoingDTO->toArray());
            /** @var TransferIncoming $outgoing */
            $incoming = $this->transferIncomingRepository->create($incomingDTO->toArray());

            $transferBetween = TransferBetween::create([
                'transfer_outgoing_id' => $outgoing->id,
                'transfer_incoming_id' => $incoming->id,
            ]);

            $this->commissionService->makeFee($outgoing);

            $this->createTransferHistory($outgoing, TransferHistoryActionEnum::INIT->value);
            $this->createTransferHistory($incoming, TransferHistoryActionEnum::INIT->value);

            return $transferBetween;
        });

        return $transfers;
    }

    /**
     * @throws GraphqlException
     */
    public function updateTransfer(TransferBetween $transfer, array $args, int $operationType): TransferBetween
    {
        if ($transfer->transferOutgoing->status_id !== PaymentStatusEnum::UNSIGNED->value) {
            throw new GraphqlException('Transfer status is not Unsigned');
        }
        if ($transfer->transferIncoming->status_id !== PaymentStatusEnum::UNSIGNED->value) {
            throw new GraphqlException('Transfer status is not Unsigned');
        }

        $fromAccount = Account::findOrFail($args['from_account_id']);
        $toAccount = Account::findOrFail($args['to_account_id']);

        $this->validateCreateTransfer($fromAccount, $toAccount, $operationType);

        $outgoingDTO = TransformerDTO::transform(CreateTransferOutgoingBetweenUsersDTO::class, $fromAccount, $toAccount, $operationType, $args);
        $incomingDTO = TransformerDTO::transform(CreateTransferIncomingBetweenUsersDTO::class, $toAccount, $fromAccount, $operationType, $args, $outgoingDTO->payment_number, $outgoingDTO->created_at);

        $transfers = DB::transaction(function () use ($transfer, $outgoingDTO, $incomingDTO) {
            /** @var TransferOutgoing $outgoing */
            $outgoing = $this->transferOutgoingRepository->update($transfer->transferOutgoing, $outgoingDTO->toArray());
            /** @var TransferIncoming $incoming */
            $incoming = $this->transferIncomingRepository->update($transfer->transferIncoming, $incomingDTO->toArray());

            $this->commissionService->makeFee($outgoing);

            $this->createTransferHistory($outgoing)->createTransferHistory($incoming);

            return $transfer->refresh();
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
            case PaymentStatusEnum::CANCELED->value:
                $this->updateTransferStatusToCanceled($transfers);

                break;
            case PaymentStatusEnum::PENDING->value:
                $this->updateTransferStatusToPending($transfers);

                break;
            case PaymentStatusEnum::EXECUTED->value:
                $this->updateTransferStatusToExecuted($transfers);

                break;
            case PaymentStatusEnum::REFUND->value:
                $this->updateTransferStatusToCancelAndRefund($transfers);

                break;
        }
    }

    /**
     * @throws GraphqlException
     */
    private function updateTransferStatusToCanceled(array $transfers): void
    {
        DB::transaction(function () use ($transfers) {
            $this->transferOutgoingRepository->update($transfers['outgoing'], [
                'status_id' => PaymentStatusEnum::CANCELED->value,
            ]);

            $this->transferIncomingRepository->update($transfers['incoming'], [
                'status_id' => PaymentStatusEnum::CANCELED->value,
            ]);

            $this->createTransferHistory($transfers['outgoing']);
            $this->createTransferHistory($transfers['incoming']);
        });
    }

    private function updateTransferStatusToCancelAndRefund(array $transfers): void
    {
        DB::transaction(function () use ($transfers) {
            $this->transferOutgoingService->updateTransferStatusToCancelAndRefund($transfers['outgoing']);

            $this->transferIncomingRepository->update($transfers['incoming'], ['status_id' => PaymentStatusEnum::CANCELED->value]);

            $this->createTransferHistory($transfers['incoming']);

            // Create OWT
            $transferDto = TransformerDTO::transform(CreateTransferOutgoingRefundDTO::class, $transfers['incoming']->toArray(), OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value);
            $this->transferOutgoingService->createTransfer($transferDto->toArray(), OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value);
        });
    }

    /**
     * @throws GraphqlException
     */
    private function updateTransferStatusToPending(array $transfers): void
    {
        DB::transaction(function () use ($transfers) {
            $this->transferOutgoingService->updateTransferStatusToPending($transfers['outgoing']);
            $this->transferIncomingService->updateTransferStatusToPending($transfers['incoming']);
        });
    }

    /**
     * @throws GraphqlException
     */
    private function updateTransferStatusToExecuted(array $transfers): void
    {
        DB::beginTransaction();

        try {
            $fromAccount = Account::find($transfers['outgoing']->account_id);
            $toAccount = Account::find($transfers['incoming']->account_id);

            $transactionOutgoing = TransformerDTO::transform(TransactionDTO::class, $transfers['outgoing'], $fromAccount, $toAccount);
            $transactionIncoming = TransformerDTO::transform(TransactionDTO::class, $transfers['incoming'], $fromAccount, $toAccount);

            $this->transferOutgoingService->updateTransferStatusToExecuted($transfers['outgoing'], $transactionOutgoing);
            $this->transferIncomingService->updateTransferStatusToExecuted($transfers['incoming'], $transactionIncoming);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw new GraphqlException($e->getMessage(), 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @throws GraphqlException
     */
    private function validateUpdateTransferStatus(array $transfers, array $args): void
    {
        foreach ($transfers as $transfer) {
            $statusId = $transfer['status_id'];
            $allowedStatuses = [];
            $errorMessage = '';
            $allowedOperations = [
                OperationTypeEnum::BETWEEN_ACCOUNT->value,
                OperationTypeEnum::BETWEEN_USERS->value,
            ];

            if (! in_array($transfer->operation_type_id, $allowedOperations)) {
                throw new GraphqlException('This operation is not allowed for this transfer', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            switch ($statusId) {
                case PaymentStatusEnum::UNSIGNED->value:
                    $allowedStatuses = [
                        PaymentStatusEnum::CANCELED->value,
                        PaymentStatusEnum::PENDING->value,
                    ];

                    break;
                case PaymentStatusEnum::PENDING->value:
                    $allowedStatuses = [
                        PaymentStatusEnum::ERROR->value,
                        PaymentStatusEnum::CANCELED->value,
                        PaymentStatusEnum::EXECUTED->value,
                    ];

                    break;
                case PaymentStatusEnum::SENT->value:
                    $allowedStatuses = [
                        PaymentStatusEnum::EXECUTED->value,
                    ];

                    break;
                case PaymentStatusEnum::CANCELED->value:
                    $errorMessage = 'Transfer has final status which is Canceled';

                    break;
                case PaymentStatusEnum::REFUND->value:
                    $errorMessage = 'Transfer has final status which is Refund';

                    break;
                case PaymentStatusEnum::EXECUTED->value:
                    $allowedStatuses = [
                        PaymentStatusEnum::REFUND->value,
                    ];

                    break;
            }

            if (!in_array($args['status_id'], $allowedStatuses)) {
                if (empty($errorMessage)) {
                    $errorMessage = sprintf('This status is not allowed for transfer which has %s status', PaymentStatusEnum::tryFrom($statusId)->toString());
                }

                throw new GraphqlException($errorMessage, 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }

    /**
     * @throws GraphqlException
     */
    private function validateCreateTransfer(Account $fromAccount, Account $toAccount, int $operationType): void
    {
        if (! $fromAccount) {
            throw new GraphqlException('From account not found', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! $toAccount) {
            throw new GraphqlException('To account not found', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($operationType == OperationTypeEnum::BETWEEN_USERS->value) {
            if ($fromAccount->owner_id == $toAccount->owner_id) {
                throw new GraphqlException('This operation is not allowed for the same accounts owner', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        if ($fromAccount->currencies->id != $toAccount->currencies->id) {
            throw new GraphqlException('Account currencies are different', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @throws GraphqlException
     */
    public function attachFiles(TransferBetween $transfer, array $fileIds): void
    {
        try {
            DB::beginTransaction();

            $this->transferOutgoingRepository->attachFileById($transfer->transferOutgoing, $fileIds);
            $this->transferIncomingRepository->attachFileById($transfer->transferIncoming, $fileIds);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @throws GraphqlException
     */
    public function detachFiles(TransferBetween $transfer, array $fileIds): void
    {
        try {
            DB::beginTransaction();

            $this->transferOutgoingRepository->detachFileById($transfer->transferOutgoing, $fileIds);
            $this->transferIncomingRepository->detachFileById($transfer->transferIncoming, $fileIds);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }
}
