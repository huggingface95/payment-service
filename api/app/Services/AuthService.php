<?php

namespace App\Services;

use App\Enums\ClientTypeEnum;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends AbstractService
{
    public const GUARD_TYPE_MEMBER = 'api';
    public const GUARD_TYPE_APPLICANT = 'api_client';

    public function getUserByClientId(int $client_type_id, int $id): Members|ApplicantIndividual|null
    {
        return match($client_type_id) {
            2 => Members::find($id),
            3 => ApplicantIndividual::find($id),

            default => null,
        };
    }

    public function getUserByGuard(string $client_type, int $id): Members|ApplicantIndividual|null
    {
        return match($client_type) {
            self::GUARD_TYPE_MEMBER => Members::find($id),
            self::GUARD_TYPE_APPLICANT => ApplicantIndividual::find($id),

            default => null,
        };
    }

    public function getClientTypeIdByGuard(string $guard): int|null
    {
        return match($guard) {
            self::GUARD_TYPE_MEMBER => 2,
            self::GUARD_TYPE_APPLICANT => 3,

            default => null,
        };
    }

    public function getGuardByClientType(string $client_type = null): string
    {
        return $client_type === ClientTypeEnum::APPLICANT->toString() ? self::GUARD_TYPE_APPLICANT : self::GUARD_TYPE_MEMBER;
    }

    public function getClientTypeByToken(): string|null
    {
        JWTAuth::parseToken();
        $clientType = JWTAuth::getPayload()->get('client_type');

        return $clientType;
    }
}
