<?php

namespace App\Enums;

enum FeePeriodEnum: int
{
    case EACH_TIME = 1;
    case DAILY = 2;
    case WEEKLY = 3;
    case MONTHLY = 4;
    case YEARLY = 5;
    case OTHER_SCHEDULE = 6;

    public function toString(): string
    {
        return match ($this) {
            self::EACH_TIME => 'Each time',
            self::DAILY => 'Daily',
            self::WEEKLY => 'Weekly',
            self::MONTHLY => 'Monthly',
            self::YEARLY => 'Yearly',
            self::OTHER_SCHEDULE => 'Other schedule',
        };
    }
}
