<?php

namespace App\Enums;

enum ApplicantStateReasonEnum: int
{
    case KYC = 1;
    case DOCUMENTS_EXPIRED = 2;
    case FINANCIAL_MONITORING = 3;

    public function toString(): string
    {
        return match ($this) {
            self::KYC => 'Kyc',
            self::DOCUMENTS_EXPIRED => 'Documents Expired',
            self::FINANCIAL_MONITORING => 'Financial Monitoring',
        };
    }
}
