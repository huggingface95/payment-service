<?php

namespace App\Enums;

enum FeeModeEnum: int
{
    case RANGE = 1;
    case FIX = 2;
    case PERCENT = 3;
    case BASE = 4;
    case PROVIDER = 5;
    case QUOTEPROVIDER = 6;
    case MARGIN = 7;

    public function toString(): string
    {
        return match ($this) {
            self::RANGE => 'Range',
            self::FIX => 'Fix',
            self::PERCENT => 'Percent',
            self::BASE => 'Base',
            self::PROVIDER => 'Provider',
            self::QUOTEPROVIDER => 'QuoteProvider',
            self::MARGIN => 'Margin',
        };
    }
}
