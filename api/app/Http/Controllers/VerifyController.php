<?php

namespace App\Http\Controllers;

use App\Enums\ClientTypeEnum;
use App\Models\EmailVerification;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function emailVerify(Request $request): JsonResponse
    {
        $verifyClient = EmailVerification::where('token', $request->token)->first();

        if ($verifyClient) {
            if ($verifyClient->type === ClientTypeEnum::APPLICANT->toString()) {
                $user = $this->authService->getUserByClientId(ClientTypeEnum::APPLICANT->value, $verifyClient->client_id);
            }

            if ($user) {
                $user->is_verification_email = true;
                $user->save();

                $verifyClient->delete();

                response()->json(['data' => 'Email successfully verified']);
            }
        }

        return response()->json(['error' => 'Wrong token']);
    }

}
