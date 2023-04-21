<?php

namespace App\GraphQL\Mutations\Applicant;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\TransferExchange;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use App\Services\TransferExchangeService;
use Illuminate\Database\Eloquent\Builder;

class ApplicantTransferExchangeMutator extends BaseMutator
{
    public function __construct(
        protected TransferExchangeService $transferService,
        protected TransferExchangeRepositoryInterface $transferRepository
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function create($_, array $args): TransferExchange|Builder
    {
        return $this->transferService->createTransfer($args, OperationTypeEnum::EXCHANGE->value);
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferExchange|Builder
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
            'status_id' => PaymentStatusEnum::PENDING->value,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function execute($_, array $args): TransferExchange|Builder
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
