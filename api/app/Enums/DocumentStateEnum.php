<?php

namespace App\Enums;

enum DocumentStateEnum: int
{
    case PENDING = 1;
    case PROCESSING = 2;
    case CONFIRMED = 3;
    case DECLINED = 4;

    public function toString(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::CONFIRMED => 'Confirmed',
            self::DECLINED => 'Declined',
        };
    }
}
