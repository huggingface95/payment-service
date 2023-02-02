<?php

namespace App\Enums;

enum ModuleTagEnum: string
{
    case BANKING_COMMON = 'BankingCommon';
    case BANKING_SYSTEM = 'BankingSystem';
    case BANKING_ADMIN_NOTIFY = 'BankingAdminNotify';
    case KYC_COMMON = 'KYCCommon';
    case KYC_SYSTEM = 'KYCSystem';
    case KYC_ADMIN_NOTIFY = 'KYCAdminNotify';
}
