<?php

namespace App\Http\Controllers;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ClientTypeEnum;
use App\Models\EmailVerification;
use App\Services\AuthService;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected EmailService $emailService
    )
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

                $emailTemplateName = 'Registration Details';
                $emailData = [
                    'client_name' => $user->first_name,
                    'client_email' => $user->email,
                    'login_page_url' => $user->company->companySettings->client_url,
                    'forgot_page_url' => $user->company->companySettings->client_url . '/forgot_password',
                    'customer_support_url' => $user->company->companySettings->support_email,
                ];
                $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $user, $user->company, $emailTemplateName, $emailData);

                $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

                return response()->json(['data' => 'Email successfully verified']);
            }
        }

        return response()->json(['error' => 'Wrong token']);
    }

}
