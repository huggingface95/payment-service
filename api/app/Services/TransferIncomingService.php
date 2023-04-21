<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\Transfer\Create\Incoming\CreateTransferIncomingStandardDTO;
use App\DTO\TransformerDTO;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\TransferIncoming;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransferIncomingService extends AbstractService
{
    public function __construct(
        protected AccountService                      $accountService,
        protected CommissionService                   $commissionService,
        protected TransferIncomingRepositoryInterface $transferRepository,
        protected TransactionService                  $transactionService
    )
    {
    }

    public function createTransfer(array $args, int $operationType): Builder|Model
    {
        $createTransferIncoming = TransformerDTO::transform(CreateTransferIncomingStandardDTO::class, $args, $operationType);

        return DB::transaction(function () use ($createTransferIncoming) {
            /** @var TransferIncoming $transfer */
            $transfer = $this->transferRepository->createWithSwift($createTransferIncoming->toArray());

            $transactionDTO = TransformerDTO::transform(TransactionDTO::class, $transfer, $transfer->account);
            $this->commissionService->makeFee($transfer, $transactionDTO);

            return $transfer;
        });
    }

    /**
     * @throws GraphqlException
     */
    public function validateUpdateTransferStatus(TransferIncoming $transfer, array $args): void
    {
        $notAllowedStatuses = [
            PaymentStatusEnum::WAITING_EXECUTION_DATE->value,
            PaymentStatusEnum::SENT->value,
        ];

        if (in_array($args['status_id'], $notAllowedStatuses)) {
            throw new GraphqlException('This status is not allowed for this type of transfer', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        switch ($transfer->status_id) {
            case PaymentStatusEnum::PENDING->value:
                $allowedStatuses = [
                    PaymentStatusEnum::ERROR->value,
                    PaymentStatusEnum::CANCELED->value,
                    PaymentStatusEnum::EXECUTED->value,
                ];

                if (!in_array($args['status_id'], $allowedStatuses)) {
                    throw new GraphqlException('This status is not allowed for transfer which has Pending status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::ERROR->value:
                if ($args['status_id'] != PaymentStatusEnum::PENDING->value) {
                    throw new GraphqlException('This status is not allowed for transfer which has Error status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                break;
            case PaymentStatusEnum::CANCELED->value:
                throw new GraphqlException('Transfer has final status which is Canceled', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);

            case PaymentStatusEnum::EXECUTED->value:
                throw new GraphqlException('Transfer has final status which is Executed', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);

        }
    }

    /**
     * @throws GraphqlException
     */
    public function updateTransferStatus(TransferIncoming $transfer, array $args): void
    {
        $this->validateUpdateTransferStatus($transfer, $args);

        switch ($args['status_id']) {
            case PaymentStatusEnum::PENDING->value:
                $this->updateTransferStatusToPending($transfer);

                break;
            case PaymentStatusEnum::EXECUTED->value:
                $transactionDTO = TransformerDTO::transform(TransactionDTO::class, $transfer, $transfer->account);
                $this->updateTransferStatusToExecuted($transfer, $transactionDTO);

                break;
            default:
                $this->transferRepository->update($transfer, ['status_id' => $args['status_id']]);

                break;
        }
    }

    public function updateTransferStatusToPending(TransferIncoming $transfer): void
    {
        $this->transferRepository->update($transfer, [
            'status_id' => PaymentStatusEnum::PENDING->value,
        ]);
    }

    public function updateTransferStatusToExecuted(TransferIncoming $transfer, TransactionDTO $transactionDTO = null): void
    {
        DB::beginTransaction();

        try {
            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::EXECUTED->value,
            ]);

            if ($transactionDTO) {
                $this->transactionService->createTransaction($transactionDTO);
            }

            $this->accountService->addToBalance($transfer->account, $transfer->amount_debt);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::ERROR->value,
                'system_message' => $e->getMessage(),
            ]);
        }
    }

    public function attachFileById(TransferIncoming $transfer, array $fileIds): void
    {
        $this->transferRepository->attachFileById($transfer, $fileIds);
    }
}
