<?php

namespace App\Enums;

enum FeeTransferTypeEnum: int
{
    case OUTGOING = 1;
    case INCOMING = 2;

    public function toString(): string
    {
        return match ($this) {
            self::OUTGOING => 'Outgoing',
            self::INCOMING => 'Incoming',
        };
    }
}
