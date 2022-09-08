<?php

namespace App\DTO\Vv;

class VvPostBackResponse
{

    public string $status;

    public string $action;

    public string $token;

    public object $data;


    public static function transform(array $inputs, string $token): self
    {
        $dto = new self();
        $dto->status = $inputs['status'];
        $dto->action = $inputs['action'];
        $dto->data = json_decode($inputs['data']);
        $dto->token = $token;

        return $dto;
    }
}
