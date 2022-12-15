<?php

namespace App\Enums;

enum ModuleTagEnum: string
{
    case BANKING_COMMON = 'Banking: Common';
    case BANKING_SYSTEM = 'Banking: System';
    case BANKING_ADMIN_NOTIFY = 'Banking: Admin Notify';
    case KYC_COMMON = 'KYC: Common';
    case KYC_SYSTEM = 'KYC: System';
    case KYC_ADMIN_NOTIFY = 'KYC: Admin notify';
}
