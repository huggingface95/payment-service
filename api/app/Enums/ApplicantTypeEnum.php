<?php

namespace App\Enums;

enum ApplicantTypeEnum: int
{
    case INDIVIDUAL = 1;
    case COMPANY = 2;

    public function toString(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'ApplicantIndividual',
            self::COMPANY => 'ApplicantCompany',
        };
    }
}
