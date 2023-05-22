<?php

namespace App\Enums;

enum TransferHistoryCommentEnum: string
{
    case ERROR = 'Transfer was failed';
    case PENDING = 'Transfer was created and is awaiting approval';
    case SENT = 'Transfer has been proceeded and sent to recipient';
    case CANCELED = 'Transfer has been closed or canceled';
    case EXECUTED = 'Transfer has been successfully completed';
    case REFUND = 'Transfer has been executed but then canceled';
    case WAITING_EXECUTION_DATE = 'Transfer will be completed on the specific date';
    case UNSIGNED = 'Transfer was created and is awaiting a sign';
}
