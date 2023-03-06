<?php

namespace App\Enums;

enum GroupTypeEnum: int
{
    case MEMBER = 1;
    case COMPANY = 2;
    case INDIVIDUAL = 3;

    public function toString(): string
    {
        return match ($this) {
            self::MEMBER => 'Member',
            self::COMPANY => 'Company',
            self::INDIVIDUAL => 'Individual',
        };
    }
}
