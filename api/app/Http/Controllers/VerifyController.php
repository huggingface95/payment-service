<?php

namespace App\Http\Controllers;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\Email\Request\EmailMembersRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ApplicantVerificationStatusEnum;
use App\Models\ApplicantIndividual;
use App\Models\Members;
use App\Services\AuthService;
use App\Services\EmailService;
use App\Services\VerifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected EmailService $emailService,
        protected VerifyService $verifyService
    ) {
    }

    public function emailVerify(Request $request): JsonResponse
    {
        $user = $this->verifyService->getVerifyUserModelByToken($request->token);

        if ($user instanceof ApplicantIndividual) {
            $user->email_verification_status_id = ApplicantVerificationStatusEnum::VERIFIED->value;
            $user->is_active = true;
            $user->two_factor_auth_setting_id = 2;
            $user->save();

            $this->verifyService->deleteVerifyCode($request->token);

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

        if ($user instanceof Members) {
            $emailTemplateSubject = 'Change Email Successful';
            $emailData = [
                'client_name' => $user->first_name,
                'email' => $request->email,
            ];
            $emailDTO = TransformerDTO::transform(EmailMembersRequestDTO::class, $user, $emailData, $emailTemplateSubject);
            $this->emailService->sendMemberEmailByMemberDto($emailDTO);
            $user->email_verification = ApplicantVerificationStatusEnum::VERIFIED->value;
            $user->email = $request->email;
            $user->save();

            $this->verifyService->deleteVerifyCode($request->token);

            return response()->json(['data' => 'Email successfully changed']);
        }

        return response()->json(['error' => 'Wrong token']);
    }

}
