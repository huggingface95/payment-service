<?php

namespace App\Services;

use App\DTO\Transaction\TransactionDTO;
use App\Models\Transactions;

class TransactionService extends AbstractService
{
    public function createTransaction(TransactionDTO $transaction): void
    {
        Transactions::create([
            'company_id' => $transaction->company_id,
            'currency_src_id' => $transaction->currency_src_id,
            'currency_dst_id' => $transaction->currency_dst_id,
            'account_src_id' => $transaction->account_src_id,
            'account_dst_id' => $transaction->account_dst_id,
            'balance_prev' => $transaction->balance_prev,
            'balance_next' => $transaction->balance_next,
            'amount' => $transaction->amount,
            'txtype' => $transaction->txtype,
            'created_at' => $transaction->created_at,
            'updated_at' => $transaction->updated_at,
            'transfer_id' => $transaction->transfer_id,
            'transfer_type' => $transaction->transfer_type,
        ]);
    }
}
