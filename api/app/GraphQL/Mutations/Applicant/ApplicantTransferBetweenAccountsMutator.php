<?php

namespace App\GraphQL\Mutations\Applicant;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\TransferBetween;
use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Repositories\TransferBetweenRepository;
use App\Services\CommissionService;
use App\Services\TransferBetweenUsersService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicantTransferBetweenAccountsMutator extends BaseMutator
{
    public function __construct(
        protected CommissionService $commissionService,
        protected TransferBetweenRepository $transferRepository,
        protected TransferBetweenUsersService $transferService,
        protected TransferOutgoingRepositoryInterface $transferOutgoingRepository,
        protected TransferIncomingRepositoryInterface $transferIncomingRepository
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function cancel($_, array $args): TransferBetween
    {
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->updateTransferStatus([
            'incoming' => $transfer->transferIncoming,
            'outgoing' => $transfer->transferOutgoing,
        ], [
            'status_id' => PaymentStatusEnum::CANCELED->value,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function create($_, array $args): array
    {
        $transfer = $this->transferService->createTransfer($args, OperationTypeEnum::BETWEEN_ACCOUNT->value);

        $fees = $this->commissionService->getAllCommissions($transfer->transferOutgoing);

        return [
            'id'                => $transfer->id,
            'transfer_incoming' => $transfer->transferIncoming,
            'transfer_outgoing' => $transfer->transferOutgoing,
            'fee_amount'        => Str::decimal($fees['fee_amount']),
            'final_amount'      => Str::decimal($fees['amount_debt']),
        ];
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferBetween|Model|Builder|null
    {
        /** @var TransferBetween $transfer */
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->updateTransfer($transfer, $args, OperationTypeEnum::BETWEEN_ACCOUNT->value);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function attachFile($_, array $args): TransferBetween
    {
        /** @var TransferBetween $transfer */
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->attachFiles($transfer, $args['file_id']);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferBetween
    {
        if (empty($args['code'])) {
            throw new GraphqlException('The "code" field is required and must not be empty.', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var TransferBetween $transfer */
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->updateTransferStatus([
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
    public function execute($_, array $args): TransferBetween
    {
        /** @var TransferBetween $transfer */
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->updateTransferStatus([
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
    public function refund($_, array $args): TransferBetween
    {
        /** @var TransferBetween $transfer */
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->updateTransferStatus([
            'incoming' => $transfer->transferIncoming,
            'outgoing' => $transfer->transferOutgoing,
        ], [
            'status_id' => PaymentStatusEnum::REFUND->value,
        ]);

        return $transfer;
    }
}
