<?php

namespace App\DTO\Email;


class EmailRequestDTO
{
    public int $id;
    public string $type;
    public string $status;
    public string $message;
    public ?array $error;
    public object $data;
    public object $details;

    public function __invoke(): EmailRequestDTO
    {
        $data = func_get_arg(0);

        $this->id = $data->id;
        $this->type = $data->type;
        $this->status = $data->status;
        $this->message = $data->message;
        $this->error = $data->messages;
        $this->data = $data->data;
        $this->details = $data->details;

        return $this;
    }
}
