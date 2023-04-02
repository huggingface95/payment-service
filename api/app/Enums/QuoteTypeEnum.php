<?php

namespace App\Enums;

enum QuoteTypeEnum: int
{
    case MANUAL = 1;
    case API = 2;

    public function toString(): string
    {
        return match ($this) {
            self::MANUAL => 'Manual',
            self::API => 'API',
        };
    }
}
