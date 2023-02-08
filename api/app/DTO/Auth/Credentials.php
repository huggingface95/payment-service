<?php

namespace App\DTO\Auth;

use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\Members;
use stdClass;

class Credentials
{
    public BaseModel $model;

    public const MEMBER = 'member';

    public static function transform(stdClass $credentials): self
    {
        $dto = new self();
        if (isset($credentials->prv) && isset($credentials->jti)) {
            $dto->model = $credentials->prv == self::MEMBER ? Members::find($credentials->jti) : ApplicantIndividual::find($credentials->jti);
        } else {
            $dto->model = new BaseModel();
        }

        return $dto;
    }
}
