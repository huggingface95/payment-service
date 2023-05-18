<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\AttachFileTrait;
use App\Models\TransferExchange;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use App\Services\TransferExchangeService;

class TransferExchangeMutator extends BaseMutator
{
    use AttachFileTrait;
    
    public function __construct(
        protected TransferExchangeService $transferService,
        protected TransferExchangeRepositoryInterface $transferRepository
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function cancel($root, array $args): TransferExchange
    {
        $transfer = $this->transferRepository->findById($args['id']);
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', 404);
        }

        $this->transferService->updateTransferStatus([
            'exchange' => $transfer,
            'incoming' => $transfer->transferIncoming,
            'outgoing' => $transfer->transferOutgoing,
        ], [
            'status_id' => PaymentStatusEnum::CANCELED->value,
        ]);

        return $transfer;
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
        if (!isset($args['code']) || empty($args['code'])) {
            throw new GraphqlException('The "code" field is required and must not be empty.', 'bad request', 400);
        }

        $transfer = $this->transferRepository->findById($args['id']);
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', 404);
        }

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
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', 404);
        }

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
