<?php

namespace App\Enums;

enum PaymentSystemTypeEnum: string
{
    case SEPA = 'SEPA';
    case SWIFT = 'SWIFT';
}
