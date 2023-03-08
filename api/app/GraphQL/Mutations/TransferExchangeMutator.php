<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Services\TransferExchangeService;

class TransferExchangeMutator extends BaseMutator
{
    public function __construct(
        protected TransferExchangeService $transferService,
        protected TransferIncomingRepositoryInterface $transferRepository
    ) {
    }

    public function create($root, array $args): TransferOutgoing
    {
        $transfer = $this->transferService->createTransfer($args, (int) OperationTypeEnum::EXCHANGE->value);

        return $transfer;
    }
}
