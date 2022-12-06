<?php

namespace App\Enums;

enum TransferOutgoingChannelEnum: int
{
    case CLIENT_DASHBOARD = 1;
    case BACK_OFFICE = 2;
    case CLIENT_MOBILE_APPLICATION = 3;

    public function toString(): string
    {
        return match ($this) {
            self::CLIENT_DASHBOARD => 'Client Dashboard',
            self::BACK_OFFICE => 'Back Office',
            self::CLIENT_MOBILE_APPLICATION => 'Client Mobile Application',
        };
    }
}
