<?php

namespace App\Enums;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Members;

enum GuardEnum: string
{
    case GUARD_MEMBER = 'api';
    case GUARD_INDIVIDUAL = 'api_client';
    case GUARD_CORPORATE = 'api_corporate';

    public function toString(): string
    {
        return match ($this) {
            self::GUARD_MEMBER => class_basename(Members::class),
            self::GUARD_INDIVIDUAL => class_basename(ApplicantIndividual::class),
            self::GUARD_CORPORATE => class_basename(ApplicantCompany::class),
        };
    }
}
