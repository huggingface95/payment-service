<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case PENDING = 1;
    case SENT = 2;
    case ERROR = 3;
    case CANCELED = 4;
    case UNSIGNED = 5;
    case WAITING_EXECUTION_DATE = 6;
    case EXECUTED = 7;

    public function toString(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SENT => 'Sent',
            self::ERROR => 'Error',
            self::CANCELED => 'Canceled',
            self::UNSIGNED => 'Unsigned',
            self::WAITING_EXECUTION_DATE => 'Waiting execution date',
            self::EXECUTED => 'Executed',
        };
    }
}
