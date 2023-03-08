<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Services\TransferIncomingService;

class TransferIncomingMutator extends BaseMutator
{
    public function __construct(
        protected TransferIncomingService $transferService,
        protected TransferIncomingRepositoryInterface $transferRepository
    ) {
    }

    public function create($root, array $args): TransferIncoming
    {
        $transfer = $this->transferService->createTransfer($args, (int) OperationTypeEnum::INCOMING_WIRE_TRANSFER->value);

        if ($transfer) {
            $this->transferService->attachFileById($transfer, $args['file_id'] ?? []);
        }

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferIncoming
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, $args);

        return $transfer;
    }
}
