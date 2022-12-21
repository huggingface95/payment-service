<?php

namespace App\GraphQL\Mutations\Applicant;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ClientTypeEnum;
use App\Events\Applicant\ApplicantIndividualSentEmailPasswordResetEvent;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\ApplicantIndividual;
use App\Services\AuthService;
use App\Services\EmailService;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class ApplicantSettingsMutator extends BaseMutator
{

    public function __construct(
        protected AuthService $authService,
        protected EmailService $emailService
    ) {
    }

    /**
     * @param  $_
     * @param  array  $args
     * @return array
     */
    public function setPassword($_, array $args)
    {
        $applicant = auth()->user();

        $this->checkCurrentPassword($args, $applicant);

        if ($applicant->two_factor_auth_setting_id == 2 && $applicant->google2fa_secret) {
            $authToken = $this->authService->getTwoFactorAuthToken($applicant, ClientTypeEnum::APPLICANT->value);

            return [
                'two_factor' => 'true',
                'auth_token' => $authToken,
            ];
        }

        throw new GraphqlException('Two factor auth is disabled', 'use');
    }

    /**
     * @param  $_
     * @param  array  $args
     * @return array
     */
    public function setPasswordWithOtp($_, array $args)
    {
        $applicant = auth()->user();

        $this->checkCurrentPassword($args, $applicant);

        $valid = Google2FA::verifyGoogle2FA($applicant->google2fa_secret, $args['code']);
        if (!$valid) {
            throw new GraphqlException('Unable to verify your code', 'use');
        }

        $applicant->update([
            'password_hash' => Hash::make($args['password']),
            'password_salt' => Hash::make($args['password']),
        ]);

        $emailTemplateName = 'Successful Password Reset';
        $emailData = [
            'client_name' => $applicant->first_name,
            'login_page_url' => $applicant->company->companySettings->client_url,
            'customer_support_url' => $applicant->company->companySettings->support_email,
        ];
        $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $applicant, $applicant->company, $emailTemplateName, $emailData);

        $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

        event(new ApplicantIndividualSentEmailPasswordResetEvent($applicant));

        return $applicant;
    }

    private function checkCurrentPassword(array $args, ApplicantIndividual $applicant)
    {
        if (!Hash::check($args['current_password'], $applicant->password_hash)) {
            throw new GraphqlException('The current password is wrong', 'use');
        }
    }
}
