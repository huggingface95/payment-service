<?php

namespace App\Traits;

use App\Enums\PaymentStatusEnum;
use App\Enums\TransferHistoryActionEnum;
use App\Enums\TransferHistoryCommentEnum;
use App\Enums\TransferTypeEnum;
use App\Models\PaymentProviderHistory;
use App\Models\TransferIncoming;
use App\Models\TransferIncomingHistory;
use App\Models\TransferOutgoing;
use App\Models\TransferOutgoingHistory;

trait TransferHistoryTrait
{
    public function createPPHistory(TransferOutgoing | TransferIncoming $transfer): self
    {
        $transferType = $transfer instanceof TransferOutgoing ?
            TransferTypeEnum::OUTGOING_WIRE_TRANSFER->toAltString() :
            TransferTypeEnum::INCOMING_WIRE_TRANSFER->toAltString();

        PaymentProviderHistory::create([
            'payment_provider_id' => $transfer->payment_provider_id,
            'transfer_id' => $transfer->id,
            'transfer_type' => $transferType,
        ]);

        return $this;
    }

    public function createTransferHistory(TransferOutgoing | TransferIncoming $transfer, string $action = ''): self
    {
        if (empty($action)) {
            $action = $this->getAction($transfer->status_id);
        }

        if (empty($comment)) {
            $comment = $this->getComment($transfer->status_id);
        }

        $data = [
            'transfer_id' => $transfer->id,
            'status_id' => $transfer->status_id,
            'action' => $action,
            'comment' => $comment,
        ];

        if ($transfer instanceof TransferOutgoing) {
            TransferOutgoingHistory::create($data);
        } elseif ($transfer instanceof TransferIncoming) {
            TransferIncomingHistory::create($data);
        }

        return $this;
    }

    private function getAction(int $statusId): string
    {
        match ($statusId) {
            PaymentStatusEnum::PENDING->value => $action = TransferHistoryActionEnum::SIGN->value,
            PaymentStatusEnum::SENT->value => $action = TransferHistoryActionEnum::SENT->value,
            PaymentStatusEnum::ERROR->value => $action = TransferHistoryActionEnum::ERROR->value,
            PaymentStatusEnum::CANCELED->value => $action = TransferHistoryActionEnum::CANCELED->value,
            PaymentStatusEnum::UNSIGNED->value => $action = TransferHistoryActionEnum::UNSIGNED->value,
            PaymentStatusEnum::WAITING_EXECUTION_DATE->value => $action = TransferHistoryActionEnum::WAITING_EXECUTION_DATE->value,
            PaymentStatusEnum::EXECUTED->value => $action = TransferHistoryActionEnum::EXECUTED->value,
            PaymentStatusEnum::REFUND->value => $action = TransferHistoryActionEnum::REFUND->value,
        };

        return $action;
    }

    private function getComment(int $statusId): string
    {
        match ($statusId) {
            PaymentStatusEnum::PENDING->value => $comment = TransferHistoryCommentEnum::PENDING->value,
            PaymentStatusEnum::SENT->value => $comment = TransferHistoryCommentEnum::SENT->value,
            PaymentStatusEnum::ERROR->value => $comment = TransferHistoryCommentEnum::ERROR->value,
            PaymentStatusEnum::CANCELED->value => $comment = TransferHistoryCommentEnum::CANCELED->value,
            PaymentStatusEnum::UNSIGNED->value => $comment = TransferHistoryCommentEnum::UNSIGNED->value,
            PaymentStatusEnum::WAITING_EXECUTION_DATE->value => $comment = TransferHistoryCommentEnum::WAITING_EXECUTION_DATE->value,
            PaymentStatusEnum::EXECUTED->value => $comment = TransferHistoryCommentEnum::EXECUTED->value,
            PaymentStatusEnum::REFUND->value => $comment = TransferHistoryCommentEnum::REFUND->value,
        };

        return $comment;
    }
}
