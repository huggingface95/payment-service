<?php

namespace App\Enums;

enum FeeTypeEnum: int
{
    case FEES = 1;
    case SERVICE_FEE = 2;

    public function toString(): string
    {
        return match ($this) {
            self::FEES => 'Fees',
            self::SERVICE_FEE => 'Service fee',
        };
    }
}
