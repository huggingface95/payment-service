<?php

namespace App\Traits;

use App\Enums\PaymentStatusEnum;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\DB;

trait UpdateTransferStatusTrait
{
    public function updateTransferStatusToPending(TransferIncoming | TransferOutgoing $transfer): void
    {
        DB::transaction(function () use ($transfer) {
            $this->transferRepository->update($transfer, [
                'status_id' => PaymentStatusEnum::PENDING->value,
            ]);

            $this->createTransferHistory($transfer);
        });
    }
}
