<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferBetweenUsersService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransferBetweenAccountsMutator extends BaseMutator
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
        return $this->transferService->createTransfer($args, OperationTypeEnum::BETWEEN_ACCOUNT->value);
    }

    /**
     * @throws GraphqlException
     */
    public function attachFile($root, array $args): TransferIncoming|Model|Builder|null
    {
        try {
            DB::beginTransaction();
            /** @var TransferIncoming $transfer */
            $transfer = TransferIncoming::query()->with('transferBetweenOutgoing')->where('operation_type_id', OperationTypeEnum::BETWEEN_ACCOUNT->value)->findOrFail($args['transfer_incoming_id']);
            $this->transferIncomingRepository->attachFileById($transfer, $args['file_id']);
            $this->transferOutgoingRepository->attachFileById($transfer->transferBetweenOutgoing, $args['file_id']);
            DB::commit();

            return $transfer;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new GraphqlException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferIncoming
    {
        if (!isset($args['code']) || empty($args['code'])) {
            throw new GraphqlException('The "code" field is required and must not be empty.', 'bad request', 400);
        }

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
