<?php

namespace App\Enums;

enum EmailVerificationStatusEnum: int
{
    case NOT_VERIFIED = 1;
    case REQUESTED = 2;
    case VERIFIED = 3;

    public function toString(): string
    {
        return match ($this) {
            self::NOT_VERIFIED => 'Not Verified',
            self::REQUESTED => 'Requested',
            self::VERIFIED => 'Verified',
        };
    }
}
