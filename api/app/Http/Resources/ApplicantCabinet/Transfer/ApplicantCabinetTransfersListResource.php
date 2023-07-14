<?php

namespace App\Http\Resources\ApplicantCabinet\Transfer;

use App\Enums\PaymentStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ApplicantCabinetTransfersListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'transfer_id' => $this->transfer_id,
            'transfer_type' => $this->transfer_type,
            'created_at' => Carbon::parse($this->created_at)->format('Y/m/d H:i:s'),
            'amount' => $this->amount,
            'amount_debt' => $this->amount_debt,
            'company_id' => $this->company_id,
            'operation_type_id' => $this->operation_type_id,
            'transfer_type_id' => $this->transfer_type_id,
            'payment_status_id' => PaymentStatusEnum::from($this->payment_status_id)->toString(),
            'reason' => $this->reason ?? '',
            'to_account' => isset($this->to_account) ? Str::transliterate($this->to_account) : '',
            'from_account' => isset($this->from_account) ? Str::transliterate($this->from_account) : '',
            'client_to_id' => $this->client_to_id,
            'client_to_type' => $this->client_to_type,
            'client_from_id' => $this->client_from_id,
            'client_from_type' => $this->client_from_type,
            'fee_amount' => $this->fee_amount,
        ];
    }
}
