<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\TransferExchange;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use App\Services\TransferExchangeService;

class TransferExchangeMutator extends BaseMutator
{
    public function __construct(
        protected TransferExchangeService $transferService,
        protected TransferExchangeRepositoryInterface $transferRepository
    ) {
    }

    public function create($_, array $args): TransferExchange
    {
        $transfer = $this->transferService->createTransfer($args, (int) OperationTypeEnum::EXCHANGE->value);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferExchange
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus([
            'exchange' => $transfer,
            'incoming' => $transfer->transferIncoming,
            'outgoing' => $transfer->transferOutgoing,
        ], [
            'status_id' => PaymentStatusEnum::PENDING->value,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function execute($_, array $args): TransferExchange
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus([
            'exchange' => $transfer,
            'incoming' => $transfer->transferIncoming,
            'outgoing' => $transfer->transferOutgoing,
        ], [
            'status_id' => PaymentStatusEnum::EXECUTED->value,
        ]);

        return $transfer;
    }
}
