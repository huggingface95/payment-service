<?php

namespace App\GraphQL\Mutations;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\TransferTypeEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\Traits\AttachFileTrait;
use App\Models\OperationType;
use App\Models\TransferOutgoing;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\CompanyRevenueAccountService;
use App\Services\TransferOutgoingService;

class TransferOutgoingFeeMutator extends BaseMutator
{
    use AttachFileTrait;

    public function __construct(
        protected TransferOutgoingService $transferService,
        protected CompanyRevenueAccountService $companyRevenueAccountService,
        protected AccountRepository $accountRepository,
        protected TransferOutgoingRepositoryInterface $transferRepository
    ) {
    }

    public function cancel($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);

        if ($transfer) {
            $this->transferService->updateTransferStatusToCancelOrError($transfer, PaymentStatusEnum::CANCELED->value);
        }

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function create($_, array $args): TransferOutgoing
    {
        if (OperationType::find($args['operation_type_id'])->transfer_type_id !== TransferTypeEnum::FEE->value) {
            throw new GraphqlException('Operation type is not Fee');
        }

        if (! $this->companyRevenueAccountService->exist($args['company_id'], $args['currency_id'])) {
            throw new GraphqlException('Revenue Account not found in this company');
        }

        $transfer = $this->transferService->createFeeTransfer($args, $args['operation_type_id']);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found');
        }

        $this->transferService->updateTransfer($transfer, $args, OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferOutgoing
    {
        if (empty($args['code'])) {
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
}
