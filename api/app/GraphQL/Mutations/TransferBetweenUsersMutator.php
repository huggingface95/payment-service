<?php

namespace App\GraphQL\Mutations;

use App\Enums\FeeTransferTypeEnum;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferBetweenUsersService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransferBetweenUsersMutator extends BaseMutator
{
    public function __construct(
        protected TransferBetweenUsersService         $transferService,
        protected TransferOutgoingRepositoryInterface $transferOutgoingRepository,
        protected TransferIncomingRepositoryInterface $transferIncomingRepository
    )
    {
    }

    public function create($root, array $args): TransferIncoming|Model|Builder|null
    {
        return $this->transferService->createTransfer($args, OperationTypeEnum::BETWEEN_USERS->value);
    }

    public function attachFile($root, array $args): TransferOutgoing|TransferIncoming|Model|Builder|null
    {
        if ($args['type'] == FeeTransferTypeEnum::INCOMING->toString()) {
            $transfer = TransferIncoming::query()->where('operation_type_id', OperationTypeEnum::BETWEEN_USERS->value)->findOrFail($args['transfer_id']);
            return $this->transferIncomingRepository->attachFileById($transfer, $args['file_id']);
        } else {
            $transfer = TransferOutgoing::query()->where('operation_type_id', OperationTypeEnum::BETWEEN_USERS->value)->findOrFail($args['transfer_id']);
            return $this->transferOutgoingRepository->attachFileById($transfer, $args['file_id']);
        }
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
