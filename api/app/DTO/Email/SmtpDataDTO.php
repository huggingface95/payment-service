<?php

namespace App\DTO\Email;

use App\Models\EmailSmtp;

class SmtpDataDTO
{
    public string|array $to;

    public string $body;

    public string $subject;

    public static function transform(EmailSmtp $smtp, string $content, string $subject): self
    {
        $dto = new self();
        $dto->to = $smtp->replay_to;
        $dto->body = $content;
        $dto->subject = $subject;

        return $dto;
    }
}
