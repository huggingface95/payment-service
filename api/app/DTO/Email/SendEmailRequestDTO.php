<?php

namespace App\DTO\Email;


class SendEmailRequestDTO
{
    public string $content;
    public string $subject;


    public static function transform(string $content, string $subject): SendEmailRequestDTO
    {
        $dto = new self();
        $dto->content = $content;
        $dto->subject = "$subject";

        return $dto;
    }

}
