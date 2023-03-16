<?php

namespace App\Enums;

enum RespondentFeesEnum: int
{
    case CHARGED_TO_CUSTOMER = 1;
    case CHARGED_TO_BENEFICIARY = 2;
    case SHARED_FEES = 3;

    public function toString(): string
    {
        return match ($this) {
            self::CHARGED_TO_CUSTOMER => 'OUR (Charged to the ordering customer)',
            self::CHARGED_TO_BENEFICIARY => 'BEN (Charged to the beneficiary)',
            self::SHARED_FEES => 'SHA (Shared fees)',
        };
    }
}
