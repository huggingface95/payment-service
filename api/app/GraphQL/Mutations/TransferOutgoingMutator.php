<?php

namespace App\GraphQL\Mutations;

use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\TransferOutgoing;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;

class TransferOutgoingMutator extends BaseMutator
{
    public function __construct(
        protected TransferOutgoingService $transferService,
        protected AccountRepository $accountRepository,
        protected TransferOutgoingRepositoryInterface $transferRepository
    ) {
    }

    public function create($root, array $args): TransferOutgoing
    {
        $transfer = $this->transferService->createTransfer($args);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function update($_, array $args): TransferOutgoing
    {
        $transfer = $this->transferRepository->findById($args['id']);

        $this->transferService->validateUpdateTransferStatus($transfer, $args);

        switch ($args['status_id']) {
            case PaymentStatusEnum::ERROR->value:
            case PaymentStatusEnum::CANCELED->value:
                $this->transferService->updateTransferStatusToCancelOrError($transfer, $args['status_id']);

                break;
            case PaymentStatusEnum::SENT->value:
                $this->transferService->updateTransferStatusToSent($transfer);

                break;
            default:
                $this->transferRepository->update($transfer, ['status_id' => $args['status_id']]);

                break;
        }

        return $transfer;
    }
}
