<?php

namespace App\Enums;

enum ApplicantStateEnum: int
{
    case ACTIVE = 1;
    case SUSPENDED = 2;
    case BLOCKED = 3;
    
    public function toString(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::SUSPENDED => 'Suspended',
            self::BLOCKED => 'Blocked',
        };
    }
}
