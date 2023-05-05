<?php

namespace App\Http\Resources\Transfer;

use App\Enums\OperationTypeEnum;
use App\Models\TransferIncoming;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TransferOutgoing;

class TransfersListResource extends JsonResource
{
    public function toArray($request): array
    {
        $description = '';
        if (isset($this->operation_type_id)) {
            if ($this->operation_type_id == OperationTypeEnum::OUTGOING_WIRE_TRANSFER->value || $this->operation_type_id == OperationTypeEnum::INCOMING_WIRE_TRANSFER->value) {
                $description = $this->clientable->fullname ?? null;
            }

            $transaction_description = sprintf('%s %s', ($this->paymentOperation->name ?? null), $description);
        }

        $data = [
            'date_time' => $this->created_at,
            'transaction_id' => $this->id,
            'account_id' => $this->account_id,
        ];

        if ($this->resource instanceof TransferOutgoing) {
            return array_merge($data, [
                'currency' => $this->currency?->code,
                'transaction_description' => $transaction_description ?? '',
                'debit' => $this->amount,
                'status' => $this->paymentStatus?->name,
            ]);
        } elseif ($this->resource instanceof TransferIncoming) {
            return array_merge($data, [
                'recipient' => $this->recipient?->name,
                'sender' => $this->sender_name,
                'reason' => $this->reason,
                'urgency' => $this->paymentUrgency?->name,
                'fee_amount' => $this->fee_amount,
                'credit_amount' => $this->amount_debt,
                'currency' => $this->currency?->code,
                'transaction_description' => $transaction_description ?? '',
                'credit' => $this->amount,
                'status' => $this->paymentStatus?->name,
            ]);
        }

        return array_merge($data, [
            'currency' => $this->transferOutgoing?->currency?->code,
            'transaction_description' => 'Fee',
            'debit' => $this->fee,
            'credit' => $this->fee,
            'status' => $this->paymentStatus?->name,
            'recipient' => $this->recipient?->name,
            'sender' => $this->sender_name,
            'reason' => $this->reason,
            'urgency' => $this->paymentUrgency?->name,
            'fee_amount' => $this->fee_amount,
            'credit_amount' => $this->amount_debt,
        ]);
    }
}
