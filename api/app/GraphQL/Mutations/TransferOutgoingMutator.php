<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\OperationType;
use App\Models\TransferOutgoing;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;

class TransferOutgoingMutator extends BaseMutator
{
    public function __construct(
        protected TransferOutgoingService $transferService,
        protected AccountRepository $accountRepository,
        protected TransferOutgoingRepositoryInterface $transferRepository
    ) {
    }

    public function create($root, array $args): TransferOutgoing
    {
        $transfer = $this->transferService->createTransfer($args, (int) OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value);

        $this->transferService->updateTransferStatusToSent($transfer);

        if ($transfer) {
            $this->transferService->attachFileById($transfer, $args['file_id'] ?? []);
        }

        return $transfer;
    }

    public function cancelFee($root, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);

        if ($transfer) {
            $this->transferService->updateTransferStatusToCancelOrError($transfer, PaymentStatusEnum::CANCELED->value);
        }

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function createFee($root, array $args): TransferOutgoing
    {
        if (OperationType::find($args['operation_type_id'])->transfer_type_id !== TransferTypeEnum::FEE->value) {
            throw new GraphqlException('Operation type is not Fee');
        }

        $transfer = $this->transferService->createTransfer($args, $args['operation_type_id']);

        if ($transfer) {
            $this->transferService->attachFileById($transfer, $args['file_id'] ?? []);
            $this->transferService->updateTransferFeeStatusToSent($transfer);
        }

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->validateUpdateTransferStatus($transfer, $args);

        switch ($args['status_id']) {
            case PaymentStatusEnum::ERROR->value:
            case PaymentStatusEnum::CANCELED->value:
                $this->transferService->updateTransferStatusToCancelOrError($transfer, $args['status_id']);

                break;
            case PaymentStatusEnum::SENT->value:
                $this->transferService->updateTransferStatusToSent($transfer);

                break;
            default:
                $this->transferRepository->update($transfer, ['status_id' => $args['status_id']]);

                break;
        }

        return $transfer;
    }

    public function updateFee($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found');
        }
        if ($transfer->status_id !== PaymentStatusEnum::SENT->value) {
            throw new GraphqlException('Transfer status is not Sent');
        }

        $this->transferService->attachFileById($transfer, $args['file_id'] ?? []);
        $this->transferService->updateTransferFeeAmount($transfer, $args['amount']);

        return $transfer;
    }
}
