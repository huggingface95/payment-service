<?php

namespace App\Http\Resources\Account\Statement;

use App\Models\Account;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        $accountNumber = '';

        if (isset($this->transaction->account_src_id)) {
            $accountNumber = Account::find($this->transaction->account_src_id)->account_number ?? null;
        } elseif (isset($this->transaction->account_dst_id)) {
            $accountNumber = Account::find($this->transaction->account_dst_id)->account_number ?? null;
        }

        $accountClient = '';
        if (isset($this->recipient->fullname)) {
            $accountClient = $this->recipient->fullname;
        } elseif (isset($this->sender->fullname)) {
            $accountClient = $this->sender->fullname;
        }

        $senderRecipient = $this->sender_name ?? $this->recipient_name;

        return [
            'transaction_id' => $this->id,
            'amount' => $this->amount,
            'sender_recipient' => $senderRecipient,
            'reason' => $this->reason,
            'created_at' => $this->created_at,
            'account_number' => $accountNumber ?? null,
            'account_client' => $accountClient,
            'status' => $this->paymentStatus->name ?? null,
            'account_balance' => $this->transaction->balance_next ?? null,
        ];
    }
}
