<?php

namespace App\Enums;

enum TransferHistoryActionEnum: string
{
    case INIT = 'Init';
    case SIGN = 'Sign';
    case ERROR = 'Error';
    case PENDING = 'Pending';
    case SENT = 'Sent';
    case CANCELED = 'Canceled';
    case EXECUTED = 'Executed';
    case REFUND = 'Refund';
    case WAITING_EXECUTION_DATE = 'Waiting execution date';
    case UNSIGNED = 'Unsigned';
}
