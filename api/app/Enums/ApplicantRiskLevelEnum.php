<?php

namespace App\Enums;

enum ApplicantRiskLevelEnum: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

    public function toString(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
        };
    }
}
