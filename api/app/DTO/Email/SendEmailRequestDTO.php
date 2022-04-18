<?php

namespace App\DTO\Email;


class SendEmailRequestDTO
{
    public string $content;
    public string $subject;

    public function __invoke(): SendEmailRequestDTO
    {
        $this->content = func_get_arg(0);
        $this->subject = func_get_arg(1);

        return $this;
    }
}
