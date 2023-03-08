<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Services\TransferBetweenUsersService;

class TransferBetweenUsersMutator extends BaseMutator
{
    public function __construct(
        protected TransferBetweenUsersService $transferService,
        protected TransferIncomingRepositoryInterface $transferRepository
    ) {
    }

    public function create($root, array $args): TransferOutgoing
    {
        $transfer = $this->transferService->createTransfer($args, (int) OperationTypeEnum::BETWEEN_USERS->value);

        return $transfer;
    }
}
