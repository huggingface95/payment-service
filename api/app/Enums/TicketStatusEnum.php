<?php

namespace App\Enums;

enum TicketStatusEnum: int
{
    case OPENED = 1;
    case REPLY_REQUIRED = 2;
    case NO_REPLY_REQUIRED = 3;
    case CLOSED = 4;

    public function toString(): string
    {
        return match ($this) {
            self::OPENED => 'Opened',
            self::REPLY_REQUIRED => 'Reply Required',
            self::NO_REPLY_REQUIRED => 'No Reply Required',
            self::CLOSED => 'Closed',
        };
    }
}
