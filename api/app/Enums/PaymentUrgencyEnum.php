<?php

namespace App\Enums;

enum PaymentUrgencyEnum: int
{
    case STANDART = 1;
    case EXPRESS = 2;

    public function toString(): string
    {
        return match ($this) {
            self::STANDART => 'Standart',
            self::EXPRESS => 'Express',
        };
    }
}
