<?php

namespace App\GraphQL\Mutations\Applicant;

use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\GraphQL\Mutations\Traits\AttachFileTrait;
use App\Models\TransferExchange;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use App\Services\TransferExchangeService;
use Illuminate\Database\Eloquent\Builder;

class ApplicantTransferExchangeMutator extends BaseMutator
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
    public function create($_, array $args): TransferExchange|Builder
    {
        return $this->transferService->createTransfer($args);
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
    public function sign($_, array $args): TransferExchange|Builder
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
            'status_id' => PaymentStatusEnum::EXECUTED->value,
        ]);

        return $transfer;
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
}
