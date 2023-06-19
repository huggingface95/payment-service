<?php

namespace App\DTO\Auth;

use App\Enums\ClientTypeEnum;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\Members;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Credentials
{
    public BaseModel|Model $model;

    public ?string $type;

    public const MEMBER = 'member';

    public const INDIVIDUAL = 'applicant';

    public const CORPORATE = 'corporate';

    public static function transform(stdClass $credentials): self
    {
        $dto = new self();
        if (isset($credentials->prv) && isset($credentials->jti)) {
            switch ($credentials->prv) {
                case self::CORPORATE:
                    $dto->model = ApplicantCompany::query()->find($credentials->jti);
                    $dto->type = ClientTypeEnum::CORPORATE->toString();
                    break;
                case self::INDIVIDUAL:
                    $dto->model = ApplicantIndividual::query()->find($credentials->jti);
                    $dto->type = ClientTypeEnum::APPLICANT->toString();
                    break;
                default:
                    $dto->model = Members::query()->find($credentials->jti);
                    $dto->type = ClientTypeEnum::MEMBER->toString();
            }
        } else {
            $dto->model = new BaseModel();
            $dto->type = null;
        }

        return $dto;
    }
}
