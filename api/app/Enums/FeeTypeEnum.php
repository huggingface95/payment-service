<?php

namespace App\Enums;

enum FeeTypeEnum: int
{
    case FEES = 1;
    case SERVICE_FEE = 2;
    case EXCHANGE_FEE = 3;
    case BTU_FEE = 4;
    case BTA_FEE = 5;
    case TRANSFERS_FEE = 6;

    public function toString(): string
    {
        return match ($this) {
            self::FEES => 'Fees',
            self::SERVICE_FEE => 'Service fee',
            self::EXCHANGE_FEE => 'Exchange fee',
            self::BTU_FEE => 'Btu fee',
            self::BTA_FEE => 'Bta fee',
            self::TRANSFERS_FEE => 'Transfers fee',
        };
    }
}
