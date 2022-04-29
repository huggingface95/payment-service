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

    public static function transform(object $data): EmailRequestDTO
    {
        $dto = new self();

        $dto->id = $data->id;
        $dto->type = $data->type;
        $dto->status = $data->status;
        $dto->message = $data->message;
        $dto->error = $data->messages;
        $dto->data = $data->data;
        $dto->details = $data->details;

        return $dto;
    }

}
