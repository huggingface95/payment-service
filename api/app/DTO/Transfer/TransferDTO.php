<?php

namespace App\DTO\Transfer;

use App\Models\TransferOutgoing;

class TransferDTO
{
    public int $id;
    public string $currency;
    public float $amount;

    public static function transform(TransferOutgoing $transfer): self
    {
        $dto = new self();
        $dto->id = $transfer->id;
        $dto->currency = $transfer->currency->code;
        $dto->amount = $transfer->amount;

        return $dto;
    }
}
