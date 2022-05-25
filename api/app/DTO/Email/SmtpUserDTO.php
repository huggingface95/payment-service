<?php

namespace App\DTO\Email;

class SmtpUserDTO
{
//    public string $memberId;
    public string $templateId;
    public string $email;
    public string $message;

    public static function transform(object $data): SmtpUserDTO
    {
        $dto = new self();
        $dto->templateId = $data->template_id;
        $dto->email = $data->email;
        $dto->message = $data->message;

        return $dto;
    }

}
