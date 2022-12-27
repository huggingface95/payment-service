<?php

namespace App\Enums;

enum OperationTypeEnum: int
{
    case INCOMING_TRANSFER = 1;
    case BETWEEN_ACCOUNTS = 2;
    case BETWEEN_USERS = 3;
    case EXCHANGE = 4;
    case OUTGOING_TRANSFER = 5;
    case FEE = 6;

    public function toString(): string
    {
        return match ($this) {
            self::INCOMING_TRANSFER => 'Incoming Transfer',
            self::BETWEEN_ACCOUNTS => 'Between Account',
            self::BETWEEN_USERS => 'Between Users',
            self::EXCHANGE => 'Exchange',
            self::OUTGOING_TRANSFER => 'Outgoing Transfer',
            self::FEE => 'Fee',
        };
    }
}
