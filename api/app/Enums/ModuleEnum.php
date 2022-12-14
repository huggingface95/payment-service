<?php

namespace App\Enums;

enum ModuleEnum: int
{
    case KYC = 1;
    case BANKING = 2;

    public function toString(): string
    {
        return match ($this) {
            self::KYC => 'KYC',
            self::BANKING => 'Banking',
        };
    }
}
