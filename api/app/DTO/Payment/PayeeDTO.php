<?php

namespace App\DTO\Payment;

use App\Models\Payments;

class PayeeDTO
{
    public string $clientCustomerId;
    public string $walletUuid;
    public object $individual;

    public static function transform(Payments $payment): PayeeDTO
    {
        $dto = new self();

        $dto->clientCustomerId = "9999999";
        $dto->walletUuid = "9999999";
        $dto->individual = (object)[
            'lastName' => $payment->first_name,
            'firstName' => $payment->last_name,
            'email' => $payment->email,
            'phone' => $payment->phone,
            'address' => new \stdClass(),
            'document' => new \stdClass()
        ];

        return $dto;
    }

}
