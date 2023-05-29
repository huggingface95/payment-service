<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\DTO\Transfer\Create\Incoming\CreateTransferIncomingExchangeDTO;
use App\DTO\Transfer\Create\Outgoing\CreateTransferOutgoingExchangeDTO;
use App\DTO\TransformerDTO;
use App\Enums\FeeModeEnum;
use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\PriceListFee;
use App\Models\TransferExchange;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\FeeRepositoryInterface;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;
use App\Repositories\Interfaces\TransferIncomingRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Traits\TransferHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransferExchangeService extends AbstractService
{
    use TransferHistoryTrait;

    public function __construct(
        protected CommissionService $commissionService,
        protected CompanyRevenueAccountService $revenueService,
        protected FeeRepositoryInterface $feeRepository,
        protected TransferOutgoingService $transferOutgoingService,
        protected TransferIncomingService $transferIncomingService,
        protected TransferIncomingRepositoryInterface $transferIncomingRepository,
        protected TransferOutgoingRepositoryInterface $transferOutgoingRepository,
        protected TransferExchangeRepositoryInterface $transferExchangeRepository,
        protected TransactionService $transactionService,
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function createTransfer(array $args, int $operationType): Builder|Model
    {
        $fromAccount = Account::findOrFail($args['from_account_id']);
        $toAccount = Account::findOrFail($args['to_account_id']);

        $this->validateCreateTransfer($fromAccount, $toAccount);

        $data = $this->populateTransferData($args, $fromAccount, $toAccount, $operationType);

        $transfers = DB::transaction(function () use ($data, $fromAccount, $toAccount) {
            /** @var TransferOutgoing $outgoing */
            $outgoing = $this->transferOutgoingRepository->create($data['outgoing']);
            $incoming = $this->transferIncomingRepository->create($data['incoming']);
            $exchange = $this->transferExchangeRepository->create([
                'company_id' => $outgoing->company_id,
                'client_id' => $outgoing->account?->client_id,
                'client_type' => $outgoing->account?->client_type,
                'requested_by_id' => $outgoing->requested_by_id,
                'user_type' => $outgoing->user_type,
                'debited_account_id' => $outgoing->account?->id,
                'credited_account_id' => $incoming->account?->id,
                'status_id' => $outgoing->status_id,
                'transfer_outgoing_id' => $outgoing->id,
                'transfer_incoming_id' => $incoming->id,
                'exchange_rate' => Str::decimal($data['exchange_rate']),
            ]);

            $outgoing->reason = 'Exchange: Buy (Rate 1 ' . $outgoing->currency->code . '-> ' . (1 * $exchange->exchange_rate) . ' ' . $incoming->currency->code . ')';

            $transactionOutgoing = TransformerDTO::transform(TransactionDTO::class, $outgoing, $fromAccount, $toAccount);
            $this->commissionService->makeFee($outgoing, $transactionOutgoing);

            $this->createTransferHistory($outgoing)->createPPHistory($outgoing);
            $this->createTransferHistory($incoming)->createPPHistory($incoming);

            return [
                'outgoing' => $outgoing,
                'incoming' => $incoming,
                'exchange' => $exchange,
            ];
        });

        return $transfers['exchange'];
    }

    public function getAllExchangeCommissions(array $args, TransferOutgoing $transfer, TransactionDTO $transaction, Account $fromAccount, Account $toAccount): array
    {
        $fees = $this->commissionService->getAllCommissions($transfer, $transaction);
        $rate = $this->getExchangeRate($args, $fromAccount, $toAccount);

        $args['amount'] = $args['amount'] - $fees['fee_total'];
        $amount = $this->getExchangeAmount($args, $fromAccount, $toAccount);

        return [
            'fee_amount' => $fees['fee_amount'],
            'fee_qoute' => $fees['fee_qp'],
            'fee_total' => $fees['fee_total'],
            'rate' => $rate,
            'converted_amount' => $amount,
        ];
    }

    /**
     * @throws GraphqlException
     */
    private function getExchangeRate(array $args, Account $fromAccount, Account $toAccount): float
    {
        $quoteProvider = PriceListFee::find($args['price_list_fee_id'])?->quoteProvider ??
            throw new GraphqlException('Quote provider not found. Please setup quote provider');

        $exchageRate = $quoteProvider->currencyExchangeRates
            ->where('currency_from_id', $fromAccount->currencies->id)
            ->where('currency_to_id', $toAccount->currencies->id)
            ->first()?->rate;

        if ($exchageRate === null) {
            throw new GraphqlException('Exchange rate not found', Response::HTTP_BAD_REQUEST);
        }

        if (! empty($quoteProvider->margin_commission)) {
            $exchageRate += $exchageRate * ($quoteProvider->margin_commission / 100);
        }

        return $exchageRate;
    }

    /**
     * @throws GraphqlException
     */
    private function getExchangeAmount(array $args, Account $fromAccount, Account $toAccount): float
    {
        $exchageRate = $this->getExchangeRate($args, $fromAccount, $toAccount);

        return $args['amount'] * $exchageRate;
    }

    /**
     * @throws GraphqlException
     */
    private function populateTransferData(array $args, Account $fromAccount, Account $toAccount, int $operationType): array
    {
        $toAmount = (string) $this->getExchangeAmount($args, $fromAccount, $toAccount);

        $outgoingDTO = TransformerDTO::transform(CreateTransferOutgoingExchangeDTO::class, $fromAccount, $operationType, $args['amount'], $args['price_list_fee_id']);
        $incomingDTO = TransformerDTO::transform(CreateTransferIncomingExchangeDTO::class, $toAccount, $operationType, $toAmount, $outgoingDTO->payment_number, $outgoingDTO->created_at, $args['price_list_fee_id']);

        return [
            'outgoing' => $outgoingDTO->toArray(),
            'incoming' => $incomingDTO->toArray(),
            'exchange_rate' => $this->getExchangeRate($args, $fromAccount, $toAccount),
        ];
    }

    public function updateTransfer(TransferExchange $transfer, array $args): Builder|Model
    {
        if ($transfer->transferOutgoing->status_id !== PaymentStatusEnum::UNSIGNED->value) {
            throw new GraphqlException('Transfer status is not Unsigned');
        }
        if ($transfer->transferIncoming->status_id !== PaymentStatusEnum::UNSIGNED->value) {
            throw new GraphqlException('Transfer status is not Unsigned');
        }

        $fromAccount = Account::findOrFail($args['from_account_id']);
        $toAccount = Account::findOrFail($args['to_account_id']);

        $this->validateCreateTransfer($fromAccount, $toAccount);

        $data = $this->populateTransferData($args, $fromAccount, $toAccount, OperationTypeEnum::EXCHANGE->value);

        $transfers = DB::transaction(function () use ($data, $transfer, $fromAccount, $toAccount) {
            /** @var TransferOutgoing $outgoing */
            $outgoing = $this->transferOutgoingRepository->update($transfer->transferOutgoing, $data['outgoing']);
            $incoming = $this->transferIncomingRepository->update($transfer->transferIncoming, $data['incoming']);
            $exchange = $this->transferExchangeRepository->update($transfer, [
                'company_id' => $outgoing->company_id,
                'client_id' => $outgoing->account?->client_id,
                'client_type' => $outgoing->account?->client_type,
                'requested_by_id' => $outgoing->requested_by_id,
                'user_type' => $outgoing->user_type,
                'debited_account_id' => $outgoing->account?->id,
                'credited_account_id' => $incoming->account?->id,
                'exchange_rate' => Str::decimal($data['exchange_rate']),
            ]);

            $outgoing->reason = 'Exchange: Buy (Rate 1 ' . $outgoing->currency->code . '-> ' . (1 * $exchange->exchange_rate) . ' ' . $incoming->currency->code . ')';

            $transactionOutgoing = TransformerDTO::transform(TransactionDTO::class, $outgoing, $fromAccount, $toAccount);
            $this->commissionService->makeFee($outgoing, $transactionOutgoing);

            $this->createTransferHistory($outgoing)->createPPHistory($outgoing);
            $this->createTransferHistory($incoming)->createPPHistory($incoming);

            return [
                'outgoing' => $outgoing,
                'incoming' => $incoming,
                'exchange' => $exchange,
            ];
        });

        return $transfers['exchange'];
    }

    /**
     * @throws GraphqlException
     */
    public function updateTransferStatus(array $transfers, array $args): void
    {
        $this->validateUpdateTransferStatus($transfers, $args);

        switch ($args['status_id']) {
            case PaymentStatusEnum::CANCELED->value:
                $this->updateTransferStatusToCanceled($transfers);

                break;
            case PaymentStatusEnum::PENDING->value:
                $this->updateTransferStatusToPending($transfers);

                break;
            case PaymentStatusEnum::EXECUTED->value:
                $this->updateTransferStatusToExecuted($transfers);

                break;
        }
    }

    private function updateTransferStatusToCanceled(array $transfers): void
    {
        DB::transaction(function () use ($transfers) {
            $this->transferOutgoingRepository->update($transfers['outgoing'], [
                'status_id' => PaymentStatusEnum::CANCELED->value,
            ]);

            $this->transferIncomingRepository->update($transfers['incoming'], [
                'status_id' => PaymentStatusEnum::CANCELED->value,
            ]);

            $this->transferExchangeRepository->update($transfers['exchange'], [
                'status_id' => PaymentStatusEnum::CANCELED->value,
            ]);

            $this->createTransferHistory($transfers['outgoing']);
            $this->createTransferHistory($transfers['incoming']);
        });
    }

    public function updateTransferStatusToPending(array $transfers): void
    {
        DB::transaction(function () use ($transfers) {
            $this->transferOutgoingService->updateTransferStatusToPending($transfers['outgoing']);
            $this->transferIncomingService->updateTransferStatusToPending($transfers['incoming']);

            $this->transferExchangeRepository->update($transfers['exchange'], [
                'status_id' => PaymentStatusEnum::PENDING->value,
            ]);
        });
    }

    /**
     * @throws GraphqlException
     */
    public function updateTransferStatusToExecuted(array $transfers): void
    {
        DB::beginTransaction();

        try {
            $fromAccount = Account::find($transfers['outgoing']->account_id);
            $toAccount = Account::find($transfers['incoming']->account_id);

            $transactionOutgoing = TransformerDTO::transform(TransactionDTO::class, $transfers['outgoing'], $fromAccount, $toAccount);
            $transactionIncoming = TransformerDTO::transform(TransactionDTO::class, $transfers['incoming'], $fromAccount, $toAccount);
            $this->commissionService->makeFee($transfers['outgoing'], $transactionOutgoing);

            $this->transferOutgoingService->updateTransferStatusToExecuted($transfers['outgoing'], $transactionOutgoing);
            $this->transferIncomingService->updateTransferStatusToExecuted($transfers['incoming'], $transactionIncoming);

            $qpMarginAmount = $this->feeRepository->getFeeByTypeMode($transfers['outgoing']->id, FeeModeEnum::MARGIN->value)->fee ?? 0;
            if ($qpMarginAmount > 0) {
                $this->revenueService->addToRevenueAccountBalanceByCompanyId($fromAccount->company_id, $qpMarginAmount, $toAccount->currency_id);
            }

            $this->transferExchangeRepository->update($transfers['exchange'], [
                'status_id' => PaymentStatusEnum::EXECUTED->value,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw new GraphqlException($e->getMessage(), 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @throws GraphqlException
     */
    private function validateUpdateTransferStatus(array $transfers, array $args): void
    {
        unset($transfers['exchange']);

        foreach ($transfers as $transfer) {
            if ($transfer->operation_type_id != OperationTypeEnum::EXCHANGE->value) {
                throw new GraphqlException('This operation is not allowed for this transfer', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            switch ($transfer['status_id']) {
                case PaymentStatusEnum::UNSIGNED->value:
                    $allowedStatuses = [
                        PaymentStatusEnum::ERROR->value,
                        PaymentStatusEnum::CANCELED->value,
                        PaymentStatusEnum::EXECUTED->value,
                    ];

                    if (! in_array($args['status_id'], $allowedStatuses)) {
                        throw new GraphqlException('This status is not allowed for transfer which has Unsigned status', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    break;
                case PaymentStatusEnum::CANCELED->value:
                    throw new GraphqlException('Transfer has final status which is Canceled', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                case PaymentStatusEnum::ERROR->value:
                    throw new GraphqlException('Transfer has final status which is Error', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
                case PaymentStatusEnum::EXECUTED->value:
                    throw new GraphqlException('Transfer has final status which is Executed', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }

    /**
     * @throws GraphqlException
     */
    private function validateCreateTransfer(Account $fromAccount, Account $toAccount): void
    {
        if (! $fromAccount) {
            throw new GraphqlException('From account not found', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! $toAccount) {
            throw new GraphqlException('To account not found', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($fromAccount->owner_id != $toAccount->owner_id) {
            throw new GraphqlException('Accounts do not belong to the same owner', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($fromAccount->currencies->id == $toAccount->currencies->id) {
            throw new GraphqlException('Account currencies are the same', 'use', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function attachFileById(TransferExchange $transfer, array $fileIds): void
    {
        DB::transaction(function () use ($transfer, $fileIds) {
            $this->transferOutgoingRepository->attachFileById($transfer->transferOutgoing, $fileIds);
            $this->transferIncomingRepository->attachFileById($transfer->transferIncoming, $fileIds);
        });
    }

    public function detachFileById(TransferExchange $transfer, array $fileIds): void
    {
        DB::transaction(function () use ($transfer, $fileIds) {
            $this->transferOutgoingRepository->detachFileById($transfer->transferOutgoing, $fileIds);
            $this->transferIncomingRepository->detachFileById($transfer->transferIncoming, $fileIds);
        });
    }
}
