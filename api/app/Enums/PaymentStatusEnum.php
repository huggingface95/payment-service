<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case PENDING = 1;
    case COMPLETED = 2;
    case ERROR = 3;
    case CANCELED = 4;
    case UNSIGNED = 5;
    case WAITING_EXECUTION_DATE = 6;
    case EXECUTION = 7;

    public function toString(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::ERROR => 'Error',
            self::CANCELED => 'Canceled',
            self::UNSIGNED => 'Unsigned',
            self::WAITING_EXECUTION_DATE => 'Waiting execution date',
            self::EXECUTION => 'Execution',
        };
    }
}
