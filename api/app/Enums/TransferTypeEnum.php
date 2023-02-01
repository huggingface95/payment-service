<?php

namespace App\Enums;

enum TransferTypeEnum: int
{
    case INCOMING_WIRE_TRANSFER = 1;
    case OUTGOING_WIRE_TRANSFER = 2;
    case BETWEEN_ACCOUNT = 3;
    case BETWEEN_USERS = 4;
    case EXCHANGE = 5;
    case FEE = 6;

    public function toString(): string
    {
        return match ($this) {
            self::INCOMING_WIRE_TRANSFER => 'Incoming Wire Transfer',
            self::OUTGOING_WIRE_TRANSFER => 'Outgoing Wire Transfer',
            self::BETWEEN_ACCOUNT => 'Between Account',
            self::BETWEEN_USERS => 'Between Users',
            self::EXCHANGE => 'Exchange',
            self::FEE => 'Fee',
        };
    }
}
