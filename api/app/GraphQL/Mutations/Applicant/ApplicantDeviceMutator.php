<?php

namespace App\GraphQL\Mutations\Applicant;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ClientTypeEnum;
use App\Events\Applicant\ApplicantIndividualSentEmailTrustedDeviceAddedEvent;
use App\Events\Applicant\ApplicantIndividualSentEmailTrustedDeviceRemovedEvent;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\Clickhouse\ActiveSession;
use App\Services\AuthService;
use App\Services\EmailService;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class ApplicantDeviceMutator extends BaseMutator
{
    public function __construct(
        protected AuthService $authService,
        protected EmailService $emailService
    ) {
    }

    /**
     * @param    $_
     * @param array $args
     * @return array
     * @throws GraphqlException
     */
    public function update($_, array $args)
    {
        $applicant = auth()->user();

        $device = DB::connection('clickhouse')
            ->table((new ActiveSession())->getTable())
            ->where('id', $args['id'])
            ->where('email', $applicant->email)
            ->where('provider', ClientTypeEnum::APPLICANT->toString())
            ->get();

        if (! $device) {
            throw new GraphqlException('Device not found', 'use');
        }

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
     * @param    $_
     * @param  array  $args
     * @return array
     */
    public function updateWithOtp($_, array $args)
    {
        $applicant = auth()->user();

        $valid = Google2FA::verifyGoogle2FA($applicant->google2fa_secret, $args['code']);
        if (! $valid) {
            throw new GraphqlException('Unable to verify your code', 'use');
        }

        $trusted = $args['trusted'] == true ? 'true' : 'false';
        $id = intval($args['id']);
        $rawSql = 'ALTER TABLE '.(new ActiveSession())->getTable().' UPDATE trusted='.$trusted.' WHERE id='.$id.' AND provider='.ClientTypeEnum::APPLICANT->toString().'  AND email=\''.$applicant->email.'\'';

        DB::connection('clickhouse')->statement($rawSql);

        $device = DB::connection('clickhouse')
            ->table((new ActiveSession())->getTable())
            ->where('id', $args['id'])
            ->where('email', $applicant->email)
            ->where('provider', ClientTypeEnum::APPLICANT->toString())
            ->first();

        $emailTemplateSubject = 'You have added a new Trusted device';
        $emailData = [
            'client_name' => $applicant->first_name,
            'created_at' => $device['created_at'],
            'ip' => $device['ip'],
            'device_details' => $device['platform'].' '.$device['browser'],
            'login_page_url' => $applicant->company->companySettings->client_url,
        ];
        $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $applicant, $applicant->company, $emailTemplateSubject, $emailData);

        $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

        event(new ApplicantIndividualSentEmailTrustedDeviceAddedEvent($applicant));

        return $device;
    }

    /**
     * @param    $_
     * @param  array  $args
     * @return array
     */
    public function delete($_, array $args)
    {
        $applicant = auth()->user();

        $device = DB::connection('clickhouse')
            ->table((new ActiveSession())->getTable())
            ->where('id', $args['id'])
            ->where('email', $applicant->email)
            ->where('provider', ClientTypeEnum::APPLICANT->toString())
            ->first();

        $id = intval($args['id']);
        $rawSql = 'ALTER TABLE '.(new ActiveSession())->getTable().' DELETE WHERE id='.$id.' AND provider='.ClientTypeEnum::APPLICANT->toString().' AND email=\''.$applicant->email.'\'';

        DB::connection('clickhouse')->statement($rawSql);

        $date = new \DateTime();
        $emailTemplateSubject = 'You have removed a Trusted device';
        $emailData = [
            'client_name' => $applicant->first_name,
            'date' => $date->format('Y-m-d'),
            'time_and_timezone' => $date->format('H:s:i e'),
            'ip' => $device['ip'],
            'client_device' => $device['platform'].' '.$device['browser'],
            'login_page_url' => $applicant->company->companySettings->client_url,
        ];
        $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $applicant, $applicant->company, $emailTemplateSubject, $emailData);

        $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

        event(new ApplicantIndividualSentEmailTrustedDeviceRemovedEvent($applicant));

        return $device;
    }
}
