<?php

namespace App\Services;

use App\Enums\ClientTypeEnum;
use App\Enums\MemberStatusEnum;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Models\OauthCodes;
use App\Models\OauthTokens;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends AbstractService
{
    public const GUARD_TYPE_MEMBER = 'api';

    public const GUARD_TYPE_APPLICANT = 'api_client';

    public function getUserByClientId(int $client_type_id, int $id): Members|ApplicantIndividual|null
    {
        return match ($client_type_id) {
            2 => Members::find($id),
            3 => ApplicantIndividual::find($id),

            default => null,
        };
    }

    public function getUserByGuard(string $client_type, int $id): Members|ApplicantIndividual|null
    {
        return match ($client_type) {
            self::GUARD_TYPE_MEMBER => Members::find($id),
            self::GUARD_TYPE_APPLICANT => ApplicantIndividual::find($id),

            default => null,
        };
    }

    public function getClientTypeIdByGuard(string $guard): int|null
    {
        return match ($guard) {
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
        try {
            JWTAuth::parseToken();
            $clientType = JWTAuth::getPayload()->get('client_type');
        } catch (\Exception) {
            return null;
        }

        return $clientType;
    }

    public function getTwoFactorAuthToken(Members|ApplicantIndividual $user, $clientId): string
    {
        OauthCodes::insert([
            'id' => $this->generateUniqueCode(),
            'user_id' => $user->id,
            'client_id' => $clientId,
            'revoked' => 'true',
            'expires_at' => now()->addMinutes(15),
        ]);

        return OauthTokens::select('id')
            ->where('user_id', $user->id)
            ->where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->first()
            ->id;
    }

    public function generateUniqueCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        return $code;
    }

    public function setInactive(Members|ApplicantIndividual $user): void
    {
        if ($user instanceof Members) {
            $user->member_status_id = MemberStatusEnum::INACTIVE->value;
        } else {
            $user->is_active = false;
        }

        $user->save();
    }
}
