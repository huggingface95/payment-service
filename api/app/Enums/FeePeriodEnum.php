<?php

namespace App\Enums;

enum FeePeriodEnum: int
{
    case DAILY = 1;
    case WEEKLY = 2;
    case MONTHLY = 3;
    case YEARLY = 4;
    case OTHER_SCHEDULE = 5;
    case EACH_TIME = 6;

    public function toString(): string
    {
        return match ($this) {
            self::DAILY => 'Daily',
            self::WEEKLY => 'Weekly',
            self::MONTHLY => 'Monthly',
            self::YEARLY => 'Yearly',
            self::OTHER_SCHEDULE => 'Other schedule',
            self::EACH_TIME => 'Each time',
        };
    }
}
