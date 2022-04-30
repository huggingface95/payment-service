<?php

namespace App\DTO\Payment;


use App\Models\Payments;

class PaymentDTO
{
    public int $paymentId;
    public string $currency;
    public float $amount;


    public static function transform(Payments $payment): PaymentDTO
    {
        $dto = new self();
        $dto->paymentId = $payment->id;
        $dto->currency = $payment->Currencies->code;
        $dto->amount = $payment->amount;
        return $dto;
    }

}
