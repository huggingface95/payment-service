<?php

namespace App\Http\Resources\Transfer;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferOutgoingDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'created_date' => $this->created_at,
            'transfer_id' => $this->id,
            'operation_type' => $this->paymentOperation?->name,
            'execution_date' => $this->execution_at,
            'amount' => $this->amount,
            'currency' => $this->currency?->code,
            'account_id' => $this->account_id,
            'account_client_name' => $this->account?->owner?->fullname,
            'iban' => $this->account?->account_number,
            'payment_provider_fee' => $this->fee?->fee_pp,
            'fee_amount' => $this->fee?->fee_amount,
            'final_amount_debited' => $this->amount_debt,
            'fee_account' => $this->fee?->client_id,
            'urgency' => $this->paymentUrgency?->name,
            'transfer_reason' => $this->reason,
            'status' => $this->paymentStatus?->name,
        ];
    }
}
