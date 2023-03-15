<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
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

        if ($transfer) {
            $this->transferService->attachFileById($transfer, $args['file_id'] ?? []);
        }

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, $args);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);
        $statusId = PaymentStatusEnum::PENDING->value;

        if ($transfer->execution_at != $transfer->created_at) {
            $statusId = PaymentStatusEnum::WAITING_EXECUTION_DATE->value;
        }

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => $statusId,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function send($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::SENT->value,
        ]);

        return $transfer;
    }
}
