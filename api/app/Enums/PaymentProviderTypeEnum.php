<?php

namespace App\Enums;

enum PaymentProviderTypeEnum: int
{
    case PAYMENT = 1;
    case IBAN = 2;

    public function toString(): string
    {
        return match ($this) {
            self::PAYMENT => 'PaymentProvider',
            self::IBAN => 'PaymentProviderIban',
        };
    }
}
