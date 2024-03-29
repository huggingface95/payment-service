<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ApplicantStateEnum;
use App\Enums\ApplicantVerificationStatusEnum;
use App\Enums\ModuleEnum;
use App\Events\Applicant\ApplicantIndividualSentEmailVerificationEvent;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\ClientIpAddress;
use App\Models\GroupRole;
use App\Services\EmailService;
use App\Services\VerifyService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApplicantMutator extends BaseMutator
{
    public function __construct(
        protected EmailService $emailService,
        protected VerifyService $verifyService
    ) {
    }

    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function create($root, array $args)
    {
        $password = Hash::make(Str::random(8));

        $args['password_hash'] = $password;
        $args['password_salt'] = $password;
        $args['group_type_id'] = GroupRole::INDIVIDUAL;
        $args['applicant_state_id'] = ApplicantStateEnum::ACTIVE->value;

        $applicant = ApplicantIndividual::create($args);

        if (isset($args['module_ids'])) {
            $applicant->modules()->attach(array_filter($args['module_ids'], function ($m) {
                return $m != ModuleEnum::KYC->value;
            }));
        }

        if (isset($args['group_id'])) {
            $applicant->groupRole()->sync([$args['group_id']], true);
        }

        return $applicant;
    }

    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function update($root, array $args)
    {
        if (isset($args['password'])) {
            $args['password_hash'] = Hash::make($args['password']);
            $args['password_salt'] = Hash::make($args['password']);
        }

        $applicant = ApplicantIndividual::find($args['id']);

        if (isset($args['email']) && $args['email'] != $applicant->email) {
            $applicant->email_verification_status_id = ApplicantVerificationStatusEnum::NOT_VERIFIED->value;
        }

        if (isset($args['personal_additional_fields'])) {
            $args['personal_additional_fields'] = $this->setAdditionalField($args['personal_additional_fields']);
        }
        if (isset($args['profile_additional_fields'])) {
            $args['profile_additional_fields'] = $this->setAdditionalField($args['profile_additional_fields']);
        }
        if (isset($args['contacts_additional_fields'])) {
            $args['contacts_additional_fields'] = $this->setAdditionalField($args['contacts_additional_fields']);
        }
        if (isset($args['labels'])) {
            $applicant->labels()->detach();
            $applicant->labels()->attach($args['labels']);
        }

        if (isset($args['ip_address'])) {
            $ip_address = $this->validIp($args['ip_address']);
            if (count($ip_address) > 0) {
                $applicant->ipAddress()->delete();
            }
            foreach ($ip_address as $ip) {
                ClientIpAddress::create([
                    'client_id' => $applicant->id,
                    'ip_address' => $ip,
                    'client_type' => class_basename(ApplicantIndividual::class),
                ]);
            }
        }
        $applicant->update($args);

        if (isset($args['group_id'])) {
            $applicant->groupRole()->sync([$args['group_id']], true);
        }

        if (isset($args['module_ids'])) {
            $applicant->modules()->detach();
            $applicant->modules()->attach($args['module_ids']);
        }

        return $applicant;
    }

    public function setSecurityPin($_, array $args)
    {
        $applicant = ApplicantIndividual::find($args['id']);

        $applicant->update(['security_pin' => str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT)]);

        return $applicant;
    }

    public function sendEmailVerification($_, array $args)
    {
        $applicant = ApplicantIndividual::find($args['applicant_id']);
        $company = $applicant->company;

        $verifyToken = $this->verifyService->createVerifyToken($applicant);

        $emailTemplateName = 'Sign Up: Email Confirmation';
        $emailData = [
            'client_name' => $applicant->first_name,
            'email_confirm_url' => $company->client_url.'/email/registration/verify/'.$verifyToken->token,
            'company_name' => $company->name,
        ];
        $emailDTO = TransformerDTO::transform(EmailApplicantRequestDTO::class, $applicant, $company, $emailTemplateName, $emailData);

        $this->emailService->sendApplicantEmailByApplicantDto($emailDTO);

        $applicant->email_verification_status_id = ApplicantVerificationStatusEnum::REQUESTED->value;
        $applicant->save();

        event(new ApplicantIndividualSentEmailVerificationEvent($applicant));

        return $applicant;
    }

    public function sendPhoneVerification($_, array $args)
    {
        $applicant = ApplicantIndividual::find($args['applicant_id']);

        $applicant->phone_verification_status_id = ApplicantVerificationStatusEnum::REQUESTED->value;
        $applicant->save();

        return $applicant;
    }

    public function sendEmailResetPassword($_, array $args)
    {
        $applicant = ApplicantIndividual::find($args['applicant_id']);
        if (! $applicant) {
            throw new GraphqlException('Applicant not found', 'not found', 404);
        }

        $this->emailService->sendApplicantChangePasswordEmail($applicant);

        return $applicant;
    }

    public function sendEmailRegistrationLink($_, array $args)
    {
        $applicant = ApplicantIndividual::find($args['applicant_id']);
        if (! $applicant) {
            throw new GraphqlException('Applicant not found', 'not found', 404);
        }

        $this->emailService->sendApplicantRegistrationLinkEmail($applicant);

        return $applicant;
    }

    /**
     * @throws \Throwable
     */
    public function setPassword($_, array $args): ApplicantIndividual
    {
        /** @var ApplicantIndividual $individual */
        $individual = ApplicantIndividual::query()->findOrFail($args['id']);
        $individual->updateOrFail(['password_hash'=>Hash::make($args['password']), 'password_salt'=>Hash::make($args['password_confirmation'])]);

        return $individual;
    }
}
