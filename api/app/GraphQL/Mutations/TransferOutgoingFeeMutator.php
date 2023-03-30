<?php

namespace App\GraphQL\Mutations;

use App\Enums\PaymentStatusEnum;
use App\Enums\TransferTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\OperationType;
use App\Models\TransferOutgoing;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\CompanyRevenueAccountService;
use App\Services\TransferOutgoingService;

class TransferOutgoingFeeMutator extends BaseMutator
{
    public function __construct(
        protected TransferOutgoingService             $transferService,
        protected CompanyRevenueAccountService        $companyRevenueAccountService,
        protected AccountRepository                   $accountRepository,
        protected TransferOutgoingRepositoryInterface $transferRepository
    )
    {
    }

    public function cancel($root, array $args): TransferOutgoing
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
    public function create($root, array $args): TransferOutgoing
    {
        if (OperationType::find($args['operation_type_id'])->transfer_type_id !== TransferTypeEnum::FEE->value) {
            throw new GraphqlException('Operation type is not Fee');
        }

        if (!$this->companyRevenueAccountService->exist($args['company_id'], $args['currency_id'])) {
            throw new GraphqlException('Revenue Account not found in this company');
        }

        $transfer = $this->transferService->createTransfer($args, $args['operation_type_id']);

        if ($transfer) {
            $this->transferService->attachFileById($transfer, $args['file_id'] ?? []);
        }

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);
        if (!$transfer) {
            throw new GraphqlException('Transfer not found');
        }
        if ($transfer->status_id !== PaymentStatusEnum::SENT->value) {
            throw new GraphqlException('Transfer status is not Sent');
        }

        $this->transferService->attachFileById($transfer, $args['file_id'] ?? []);
        $this->transferService->updateTransferFeeAmount($transfer, $args['amount']);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function sign($_, array $args): TransferOutgoing
    {
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
}
