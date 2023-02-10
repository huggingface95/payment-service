<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\Request\EmailApplicantRequestDTO;
use App\DTO\TransformerDTO;
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
            $applicant->labels()->detach($args['labels']);
            $applicant->labels()->attach($args['labels']);
        }

        if (isset($args['ip_address'])) {
            $ip_address = str_replace(' ', '', explode(',', $args['ip_address']));
            for ($i = 0; $i < count($ip_address); $i++) {
                if (! filter_var($ip_address[$i], FILTER_VALIDATE_IP)) {
                    throw new GraphqlException('Not a valid ip address. Address format xxx.xxx.xxx.xxx and must be comma separated', 'internal', 403);
                }
            }
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
        ApplicantIndividual::where('id', $args['id'])->update(['security_pin' => str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT)]);

        return $args;
    }

    public function sendEmailVerification($_, array $args)
    {
        $applicant = ApplicantIndividual::find($args['applicant_id']);
        $company = $applicant->company;

        $verifyToken = $this->verifyService->createVerifyToken($applicant);

        $emailTemplateName = 'Welcome! Confirm your email address';
        $emailData = [
            'client_name' => $applicant->first_name,
            'email_confirm_url' => $company->companySettings->client_url.'/email/registration/verify/'.$verifyToken->token,
            'member_company_name' => $company->name,
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
}
