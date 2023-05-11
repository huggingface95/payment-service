<?php

namespace App\Enums;

enum ClientTypeEnum: int
{
    case MEMBER = 2;
    case APPLICANT = 3;
    case CORPORATE = 4;

    public function toString(): string
    {
        return match ($this) {
            self::MEMBER => 'member',
            self::APPLICANT => 'applicant',
            self::CORPORATE => 'corporate',
        };
    }
}
