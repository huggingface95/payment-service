<?php

namespace App\DTO\Email;

use App\Models\EmailSmtp;

class SmtpConfigDTO
{
    public string $username;

    public string $password;

    public string $port;

    public string $host;

    public string $security;

    public string $subject;

    public string|array $from;

    public static function transform(EmailSmtp $smtp): self
    {
        $dto = new self();
        $dto->username = $smtp->username;
        $dto->password = $smtp->password;
        $dto->port = $smtp->port;
        $dto->host = $smtp->host_name;
        $dto->security = $smtp->security;
        $dto->subject = $smtp->from_name;
        $dto->from = $smtp->from_email;

        return $dto;
    }
}
