<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\TransferIncoming;
use App\Services\TransferBetweenUsersService;

class TransferBetweenUsersMutator extends BaseMutator
{
    public function __construct(
        protected TransferBetweenUsersService $transferService,
    ) {
    }

    public function create($root, array $args): TransferIncoming
    {
        $transfer = $this->transferService->createTransfer($args, (int) OperationTypeEnum::BETWEEN_USERS->value);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferIncoming
    {
        $transfers = $this->transferService->getTransfersByIncomingId($args['transfer_incoming_id']);

        $this->transferService->updateTransferStatus($transfers, [
            'status_id' => PaymentStatusEnum::PENDING->value,
        ]);

        return $transfers['incoming'];
    }

    /**
     * @throws GraphqlException
     */
    public function execute($_, array $args): TransferIncoming
    {
        $transfers = $this->transferService->getTransfersByIncomingId($args['transfer_incoming_id']);

        $this->transferService->updateTransferStatus($transfers, [
            'status_id' => PaymentStatusEnum::EXECUTED->value,
        ]);

        return $transfers['incoming'];
    }
}
