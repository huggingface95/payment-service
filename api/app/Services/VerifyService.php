<?php

namespace App\Services;

use App\Enums\ClientTypeEnum;
use App\Models\ApplicantIndividual;
use App\Models\EmailVerification;
use App\Models\Members;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VerifyService extends AbstractService
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function getVerifyUserModelByToken(string $token, int $tokenValidHours = 0): Members | ApplicantIndividual | null
    {
        $verifyClient = EmailVerification::where('token', $token);

        if ($tokenValidHours) {
            $dateTime = Carbon::now()->subHour($tokenValidHours)->toDateTimeString();
            $verifyClient->where('created_at', '>=', $dateTime);
        }

        $verifyClient = $verifyClient->first();

        if (! $verifyClient) {
            return null;
        }

        if ($verifyClient->type === ClientTypeEnum::APPLICANT->toString()) {
            return $this->authService->getUserByClientId(ClientTypeEnum::APPLICANT->value, $verifyClient->client_id);
        } elseif ($verifyClient->type === ClientTypeEnum::MEMBER->toString()) {
            return $this->authService->getUserByClientId(ClientTypeEnum::MEMBER->value, $verifyClient->client_id);
        }

        return null;
    }

    public function createVerifyToken(ApplicantIndividual|Members $model): EmailVerification
    {
        if ($model instanceof ApplicantIndividual) {
            $type = ClientTypeEnum::APPLICANT->toString();
        } else {
            $type = ClientTypeEnum::MEMBER->toString();
        }

        return EmailVerification::create([
            'client_id' => $model->id,
            'type' => $type,
            'token' => Str::random(64),
        ]);
    }

    public function deleteVerifyCode(string $token): void
    {
        EmailVerification::where('token', $token)->delete();
    }
}
