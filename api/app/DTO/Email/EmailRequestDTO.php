<?php

namespace App\DTO\Email;

class EmailRequestDTO
{
    public int $id;

    public string $status;

    public string $message;

    public object $data;

    public static function transform(object $data): self
    {
        $dto = new self();

        $dto->id = $data->id;
        $dto->status = $data->status;
        $dto->message = $data->message;
        $dto->data = $data->data;

        return $dto;
    }
}
