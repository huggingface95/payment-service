<?php

namespace App\DTO\Transaction;

use App\Enums\OperationTypeEnum;
use App\Models\Account;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Carbon;

class TransactionDTO
{
    public int $company_id;

    public int $currency_src_id;

    public int $currency_dst_id;

    public ?int $account_src_id = null;

    public ?int $account_dst_id = null;

    public float $balance_prev;

    public ?float $balance_next = null;

    public float $amount;

    public ?string $txtype = null;

    public string $created_at;

    public string $updated_at;

    public int $transfer_id;

    public string $transfer_type;

    public static function transform(TransferOutgoing|TransferIncoming $transfer, Account $account, Account $accountTo = null): self
    {
        $dto = new self();

        $dto->currency_src_id = $account->currency_id;
        $dto->currency_dst_id = $account->currency_id;
        $dto->balance_prev = $account->current_balance;

        switch ($transfer->operation_type_id) {
            case OperationTypeEnum::EXCHANGE->value:
                $dto->txtype = 'exchange';
                $dto->currency_src_id = $account->currency_id;
                $dto->currency_dst_id = $accountTo->currency_id;
                $dto->account_src_id = $account->id;
                $dto->account_dst_id = $accountTo->id;
                if ($transfer instanceof TransferOutgoing) {
                    $dto->balance_prev = $account->current_balance;
                    $dto->balance_next = $account->current_balance - $transfer->amount;
                } else {
                    $dto->balance_prev = $accountTo->current_balance;
                    $dto->balance_next = $accountTo->current_balance + $transfer->amount;
                }
                break;
            case OperationTypeEnum::BETWEEN_ACCOUNT->value:
            case OperationTypeEnum::BETWEEN_USERS->value:
                $dto->txtype = 'internal';
                $dto->account_src_id = $account->id;
                $dto->account_dst_id = $accountTo->id;
                if ($transfer instanceof TransferOutgoing) {
                    $dto->balance_prev = $account->current_balance;
                    $dto->balance_next = $account->current_balance - $transfer->amount;
                } else {
                    $dto->balance_prev = $accountTo->current_balance;
                    $dto->balance_next = $accountTo->current_balance + $transfer->amount;
                }
                break;
            case OperationTypeEnum::INCOMING_WIRE_TRANSFER->value:
                $dto->txtype = 'income';
                $dto->account_src_id = null;
                $dto->account_dst_id = $account->id;
                $dto->balance_next = $account->current_balance + $transfer->amount;
                break;
            case OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value:
                $dto->txtype = 'outgoing';
                $dto->account_src_id = $account->id;
                $dto->account_dst_id = null;
                $dto->balance_next = $account->current_balance - $transfer->amount;
                break;
            case OperationTypeEnum::CREDIT->value:
            case OperationTypeEnum::DEBIT->value:
            case OperationTypeEnum::SCHEDULED_FEE->value:
                $dto->txtype = 'fee';
                $dto->account_src_id = $account->id;
                $dto->account_dst_id = null;
                $dto->balance_next = $account->current_balance - $transfer->amount;
                break;
        }

        $dto->company_id = $account->company_id;
        $dto->amount = $transfer->amount;
        $dto->created_at = Carbon::now();
        $dto->updated_at = Carbon::now();
        $dto->transfer_id = $transfer->id;
        $dto->transfer_type = $transfer instanceof TransferOutgoing ? class_basename(TransferOutgoing::class) : class_basename(TransferIncoming::class);

        return $dto;
    }
}
