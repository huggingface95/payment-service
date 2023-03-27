<?php

namespace App\Http\Resources\Account;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AccountsListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'account_id' => $this->id,
            'member_company' => $this->company?->name,
            'client_name' => $this->clientable?->fullname,
            'owner_name' => $this->owner?->fullname,
            'iban_provider' => $this->paymentProviderIban?->name,
            'iban_account' => $this->account_number,
            'currency' => $this->currencies?->code,
            'is_primary' => $this->is_primary ? 'Yes' : 'No',
            'current_balance' => $this->current_balance,
            'reserved_balance' => $this->reserved_balance,
            'available_balance' => $this->available_balance,
            'total_transactions' => $this->total_transactions,
            'total_panding' => $this->total_pending_transactions,
            'last_transaction' => Carbon::parse($this->last_transaction_at)->format('Y/m/d H:i:s'),
        ];
    }
}
