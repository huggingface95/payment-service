<?php

namespace App\DTO\Vv;

use App\Models\ApplicantIndividual;

class VvPostBackResponse
{

    public string $status;

    public string $action;

    public object $data;

    public int $authorId;

    public static function transform(array $inputs): self
    {
        $dto = new self();
        $dto->status = $inputs['status'];
        $dto->action = $inputs['action'];
        $dto->data = json_decode($inputs['data']);
        $dto->authorId = ApplicantIndividual::find($inputs['client_id']);

        return $dto;
    }
}
