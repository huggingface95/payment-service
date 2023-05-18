<?php

namespace App\GraphQL\Mutations;

use App\DTO\Service\CheckLimitDTO;
use App\DTO\TransformerDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\AttachFileTrait;
use App\Models\TransferOutgoing;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\CheckLimitService;
use App\Services\TransferOutgoingService;

class TransferOutgoingMutator extends BaseMutator
{
    use AttachFileTrait;

    public function __construct(
        protected TransferOutgoingService $transferService,
        protected AccountRepository $accountRepository,
        protected TransferOutgoingRepositoryInterface $transferRepository,
        protected CheckLimitService $checkLimitService
    ) {
    }

    /**
     * @throws EmailException
     * @throws GraphqlException
     */
    public function create($root, array $args): TransferOutgoing
    {
        $this->checkLimitService->checkLimits(TransformerDTO::transform(CheckLimitDTO::class, new TransferOutgoing($args), $args['amount']));

        /** @var TransferOutgoing $transfer */
        $transfer = $this->transferService->createTransfer($args, OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferOutgoing
    {
        /** @var TransferOutgoing $transfer */
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, $args);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferOutgoing
    {
        /** @var TransferOutgoing $transfer */
        if (! isset($args['code']) || empty($args['code'])) {
            throw new GraphqlException('The "code" field is required and must not be empty.', 'bad request', 400);
        }

        $transfer = $this->transferRepository->findById($args['id']);
        $statusId = PaymentStatusEnum::PENDING->value;

        if ($transfer->execution_at != $transfer->created_at) {
            $statusId = PaymentStatusEnum::WAITING_EXECUTION_DATE->value;
        }

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => $statusId,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function send($_, array $args): TransferOutgoing
    {
        /** @var TransferOutgoing $transfer */
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::SENT->value,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function execute($_, array $args): TransferOutgoing
    {
        /** @var TransferOutgoing $transfer */
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::EXECUTED->value,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function refund($_, array $args): TransferOutgoing
    {
        /** @var TransferOutgoing $transfer */
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::REFUND->value,
        ]);

        return $transfer;
    }
}
