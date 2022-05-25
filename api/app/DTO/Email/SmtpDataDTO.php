<?php

namespace App\DTO\Email;


use App\Models\EmailSmtp;

class SmtpDataDTO
{
    public string $to;
    public string $body;

    public static function transform(EmailSmtp $smtp, string $content): SmtpDataDTO
    {
        $dto = new self();
        $dto->to = $smtp->replay_to;
        $dto->body = $content;

        return $dto;
    }
}
