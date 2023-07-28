<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\AttachFileTrait;
use App\GraphQL\Mutations\Traits\DetachFileTrait;
use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Services\TransferIncomingService;

class TransferIncomingMutator extends BaseMutator
{
    use AttachFileTrait;
    use DetachFileTrait;

    public function __construct(
        protected TransferIncomingService $transferService,
        protected TransferIncomingRepositoryInterface $transferRepository
    ) {
    }

    public function create($root, array $args): TransferIncoming
    {
        $transfer = $this->transferService->createTransfer($args, (int) OperationTypeEnum::INCOMING_WIRE_TRANSFER->value);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferIncoming
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransfer($transfer, $args, OperationTypeEnum::INCOMING_WIRE_TRANSFER->value);

        return $this->transferRepository->findById($args['id']);
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferIncoming
    {
        if (empty($args['code'])) {
            throw new GraphqlException('The "code" field is required and must not be empty.', 'bad request', 400);
        }

        $transfer = $this->transferRepository->findById($args['id']);
        $statusId = PaymentStatusEnum::PENDING->value;
        
        if ($transfer->execution_at > $transfer->created_at) {
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
    public function execute($_, array $args): TransferIncoming
    {
        /** @var TransferIncoming $transfer */
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::EXECUTED->value,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function cancel($_, array $args): TransferIncoming
    {
        /** @var TransferIncoming $transfer */
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::CANCELED->value,
        ]);

        return $transfer;
    }
}
