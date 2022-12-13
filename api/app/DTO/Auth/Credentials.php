<?php

namespace App\DTO\Auth;

use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\Members;
use stdClass;

class Credentials
{
    public BaseModel $model;

    const MEMBER = 'member';

    public static function transform(stdClass $credentials): self
    {
        $dto = new self();
        $dto->model = $credentials->prv == self::MEMBER ? Members::find($credentials->jti) : ApplicantIndividual::find($credentials->jti);

        return $dto;
    }
}
