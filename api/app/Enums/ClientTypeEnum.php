<?php

namespace App\Enums;

enum ClientTypeEnum: int
{
    case MEMBER = 2;
    case APPLICANT = 3;

    public function toString(): string
    {
        return match ($this) {
            self::MEMBER => 'member',
            self::APPLICANT => 'applicant',
        };
    }
}
