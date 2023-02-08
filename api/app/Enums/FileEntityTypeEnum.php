<?php

namespace App\Enums;

enum FileEntityTypeEnum: int
{
    case MEMBER = 1;
    case COMPANY = 2;
    case DOCUMENT = 3;
    case APPLICANT_INDIVIDUAL = 4;
    case APPLICANT_COMPANY = 5;
    case APPLICANT = 6;
    case PROJECT = 7;
    case FILE = 8;

    public function toString(): string
    {
        return match ($this) {
            self::MEMBER => 'member',
            self::COMPANY => 'company',
            self::DOCUMENT => 'document',
            self::APPLICANT_INDIVIDUAL => 'applicant_individual',
            self::APPLICANT_COMPANY => 'applicant_company',
            self::APPLICANT => 'applicant',
            self::PROJECT => 'project',
            self::FILE => 'file',
        };
    }
}
