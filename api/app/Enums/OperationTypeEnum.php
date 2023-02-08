<?php

namespace App\Enums;

enum OperationTypeEnum: int
{
    case INCOMING_WIRE_TRANSFER = 1;
    case OUTGOING_WIRE_TRANSFER = 2;
    case BETWEEN_ACCOUNT = 3;
    case BETWEEN_USERS = 4;
    case EXCHANGE = 5;
    case DEBIT = 6;
    case CREDIT = 7;
    case SCHEDULED_FEE = 8;

    public function toString(): string
    {
        return match ($this) {
            self::INCOMING_WIRE_TRANSFER => 'Incoming Transfer',
            self::OUTGOING_WIRE_TRANSFER => 'Outgoing Transfer',
            self::BETWEEN_ACCOUNT => 'Between Account',
            self::BETWEEN_USERS => 'Between Users',
            self::EXCHANGE => 'Exchange',
            self::DEBIT => 'Debit',
            self::CREDIT => 'Credit',
            self::SCHEDULED_FEE => 'Scheduled fee',
        };
    }
}
