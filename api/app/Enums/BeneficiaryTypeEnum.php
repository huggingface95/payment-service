<?php

namespace App\Enums;

enum BeneficiaryTypeEnum: int
{
    case PERSONAL = 1;
    case CORPORATE = 2;

    public function toString(): string
    {
        return match ($this) {
            self::PERSONAL => 'Personal',
            self::CORPORATE => 'Corporate',
        };
    }
}
