<?php

namespace App\Enums;

enum FeeModeEnum: int
{
    case RANGE = 1;
    case FIX = 2;
    case PERCENT = 3;

    public function toString(): string
    {
        return match ($this) {
            self::RANGE => 'range',
            self::FIX => 'fix',
            self::PERCENT => '%',
        };
    }
}
