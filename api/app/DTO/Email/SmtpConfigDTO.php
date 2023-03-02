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
        if (env('APP_ENV') == 'testing' || env('APP_ENV') == 'local') {
            $dto = new self();
            $dto->username = '';
            $dto->password = '';
            $dto->port = env('MAIL_SMTP_PORT', '1025');
            $dto->host = 'mailhog';
            $dto->security = '';
            $dto->subject = $smtp->from_name;
            $dto->from = $smtp->from_email;
        } else {
            $dto = new self();
            $dto->username = $smtp->username;
            $dto->password = $smtp->password;
            $dto->port = $smtp->port;
            $dto->host = $smtp->host_name;
            $dto->security = $smtp->security;
            $dto->subject = $smtp->from_name;
            $dto->from = $smtp->from_email;
        }

        return $dto;
    }
}
