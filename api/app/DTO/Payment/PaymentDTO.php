<?php

namespace App\DTO\Payment;

use App\Models\Payments;

class PaymentDTO
{
    public int $id;

    public string $currency;

    public float $amount;

    public static function transform(Payments $payment): self
    {
        $dto = new self();
        $dto->id = $payment->id;
        $dto->currency = $payment->Currencies->code;
        $dto->amount = $payment->amount;

        return $dto;
    }
}
