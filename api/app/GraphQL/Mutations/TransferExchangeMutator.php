<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\AttachFileTrait;
use App\GraphQL\Mutations\Traits\DetachFileTrait;
use App\Models\TransferExchange;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use App\Services\TransferExchangeService;

class TransferExchangeMutator extends BaseMutator
{
    use AttachFileTrait;
    use DetachFileTrait;

    public function __construct(
        protected TransferExchangeService $transferService,
        protected TransferExchangeRepositoryInterface $transferRepository
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function cancel($_, array $args): TransferExchange
    {
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
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
    public function update($_, array $args): TransferExchange
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransfer($transfer, $args);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferExchange
    {
        if (! isset($args['code']) || empty($args['code'])) {
            throw new GraphqlException('The "code" field is required and must not be empty.', 'bad request', 400);
        }

        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
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
