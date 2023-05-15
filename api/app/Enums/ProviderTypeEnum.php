<?php

namespace App\Enums;

enum ProviderTypeEnum: int
{
    case PAYMENT = 1;
    case IBAN = 2;
    case QUOTE = 3;

    public function toString(): string
    {
        return match ($this) {
            self::PAYMENT => 'PaymentProvider',
            self::IBAN => 'PaymentProviderIban',
            self::QUOTE => 'QuoteProvider',
        };
    }
}
