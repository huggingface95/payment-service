<?php

namespace App\Http\Resources\Transfer;

use App\Enums\OperationTypeEnum;
use App\Models\Members;
use App\Models\TransferExchange;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'requested' => $this->clientable->fullname,
                'sender' => $this->sender->fullname,
                'recipient' => $this->recipient_name,
                'reason' => $this->reason,
                'urgency' => $this->paymentUrgency?->name,
                'fee_amount' => $this->fee_amount,
                'fee_account' => ($this->fee) ? $this->fee->account->account_number : '',
                'fee_provider' => $this->fee()->where('fee_type_mode_id', 5)->where('transfer_id', $data['transaction_id'])->where('transfer_type', 'Outgoing')->first()->fee ?? 0,
                'account_number' => $this->account->account_number,
                'credit_amount' => $this->amount_debt,
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
                'fee_provider' => $this->fee()->where('fee_type_mode_id', 5)->where('transfer_id', $data['transaction_id'])->where('transfer_type', 'Incoming')->first()->fee ?? 0,
                'account_number' => $this->account->account_number,
                'credit_amount' => $this->amount_debt,
                'currency' => $this->currency?->code,
                'transaction_description' => $transaction_description ?? '',
                'credit' => $this->amount,
                'status' => $this->paymentStatus?->name,
            ]);
        } elseif ($this->resource instanceof TransferExchange) {
            return array_merge($data, [
                'requested' => ($this->clientable instanceof Members) ? 'Member' : 'Applicant',
                'client' => $this->client?->fullname,
                'debited_account' => $this->debitedAccount?->id,
                'credited_account' => $this->creditedAccount?->id,
                'quotes_provider' => $this->quoteProviders->first()->name ?? '',
                'exchange_rate' => $this->exchange_rate,
                'margin_commission' => $this->quoteProviders->first()->margin_commission ?? '',
                'margin_fee' => $this->TransferOutgoing->fee()->where('fee_type_mode_id', 7)->where('transfer_id', $this->TransferOutgoing->id)->where('transfer_type', 'Outgoing')->first()->fee ?? 0,
                'debited_amount' => $this->TransferIncoming->amount,
                'quotes_provider_fee' => $this->TransferOutgoing->amount_debt,
                'final_amount' => $this->TransferOutgoing->amount_debt,
                'currency' => $this->currency?->code,
                'credited_amount' => $this->TransferOutgoing->amount,
                'status' => $this->paymentStatus?->name,
            ]);
        }

        return [];
    }
}
