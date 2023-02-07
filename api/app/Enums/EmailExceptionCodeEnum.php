<?php

namespace App\Enums;

enum EmailExceptionCodeEnum: int
{
    case SMTP = 1;
    case TEMPLATE = 2;

    public function toString(): string
    {
        return match ($this) {
            self::SMTP => 'smtp',
            self::TEMPLATE => 'template',
        };
    }
}
