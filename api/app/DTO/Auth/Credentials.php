<?php

namespace App\DTO\Auth;

use App\Enums\ClientTypeEnum;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\Members;
use stdClass;

class Credentials
{
    public BaseModel $model;
    public ?string $type;

    public const MEMBER = 'member';

    public static function transform(stdClass $credentials): self
    {
        $dto = new self();
        if (isset($credentials->prv) && isset($credentials->jti)) {
            $dto->model = $credentials->prv == self::MEMBER ? Members::find($credentials->jti) : ApplicantIndividual::find($credentials->jti);
            $dto->type = $credentials->prv == self::MEMBER ? ClientTypeEnum::MEMBER->toString() : ClientTypeEnum::APPLICANT->toString();
        } else {
            $dto->model = new BaseModel();
            $dto->type = null;
        }

        return $dto;
    }
}
