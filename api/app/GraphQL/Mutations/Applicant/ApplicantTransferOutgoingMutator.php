<?php

namespace App\GraphQL\Mutations\Applicant;

use App\DTO\Service\CheckLimitDTO;
use App\DTO\TransformerDTO;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\EmailException;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\GraphQL\Mutations\Traits\AttachFileTrait;
use App\Models\TransferOutgoing;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\CheckLimitService;
use App\Services\TransferOutgoingService;
use Illuminate\Http\Response;

class ApplicantTransferOutgoingMutator extends BaseMutator
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
    public function create($_, array $args): TransferOutgoing
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
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }
        
        $this->transferService->updateTransfer($transfer, $args);

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
        
        /** @var TransferOutgoing $transfer */
        $transfer = $this->transferRepository->findById($args['id']);
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

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
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

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
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

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
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::REFUND->value,
        ]);

        return $transfer;
    }

    /**
     * @throws GraphqlException
     */
    public function cancel($_, array $args): TransferOutgoing
    {
        /** @var TransferOutgoing $transfer */
        $transfer = $this->transferRepository->findById($args['id']);
        if (!$transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $this->transferService->updateTransferStatus($transfer, [
            'status_id' => PaymentStatusEnum::CANCELED->value,
        ]);

        return $transfer;
    }
}
