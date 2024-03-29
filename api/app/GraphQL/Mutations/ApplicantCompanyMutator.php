<?php

namespace App\GraphQL\Mutations;

use App\DTO\Email\Request\EmailApplicantCompanyRequestDTO;
use App\DTO\TransformerDTO;
use App\Enums\ApplicantStateEnum;
use App\Enums\ApplicantVerificationStatusEnum;
use App\Enums\ModuleEnum;
use App\Events\Applicant\ApplicantIndividualSentEmailVerificationEvent;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;
use App\Models\GroupRole;
use App\Services\EmailService;
use App\Services\VerifyService;
use Illuminate\Support\Facades\DB;

class ApplicantCompanyMutator extends BaseMutator
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
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @return mixed
     * @throws GraphqlException
     */
    public function create($root, array $args)
    {
        try {
            DB::beginTransaction();

            $args['group_type_id'] = GroupRole::COMPANY;
            $args['applicant_state_id'] = ApplicantStateEnum::ACTIVE->value;
            $applicantCompany = ApplicantCompany::create($args);

            if (isset($args['owner_id']) && isset($args['owner_relation_id']) && isset($args['owner_position_id'])) {
                $this->setOwner($applicantCompany, $args);
            }

            if (isset($args['module_ids'])) {
                $applicantCompany->modules()->attach(array_filter($args['module_ids'], function ($m) {
                    return $m != ModuleEnum::KYC->value;
                }));
            }

            if (isset($args['group_id'])) {
                $applicantCompany->groupRole()->sync([$args['group_id']], true);
            }

            DB::commit();

            return $applicantCompany;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw new GraphqlException($e->getMessage(), $e->getCode());
        }
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
        $applicant = ApplicantCompany::find($args['id']);
        $args['group_type_id'] = GroupRole::COMPANY;
        if (isset($args['info_additional_fields'])) {
            $args['info_additional_fields'] = $this->setAdditionalField($args['info_additional_fields']);
        }
        if (isset($args['contacts_additional_fields'])) {
            $args['contacts_additional_fields'] = $this->setAdditionalField($args['contacts_additional_fields']);
        }

        if (isset($args['profile_additional_fields'])) {
            $args['profile_additional_fields'] = $this->setAdditionalField($args['profile_additional_fields']);
        }

        if (isset($args['owner_id']) && isset($args['owner_relation_id']) && isset($args['owner_position_id'])) {
            $this->setOwner($applicant, $args);
        }

        if (isset($args['labels'])) {
            $applicant->labels()->detach();
            $applicant->labels()->attach($args['labels']);
        }

        if (isset($args['group_id'])) {
            $applicant->groupRole()->sync([$args['group_id']], true);
        }

        if (isset($args['module_ids'])) {
            $applicant->modules()->detach();
            $applicant->modules()->attach($args['module_ids']);
        }

        $applicant->update($args);

        return $applicant;
    }

    /**
     * @param  ApplicantCompany  $applicant
     * @param  array  $args
     * @return ApplicantIndividualCompany
     *
     * @throws GraphqlException
     */
    private function setOwner(ApplicantCompany $applicant, array $args): ApplicantIndividualCompany
    {
        try {
            return ApplicantIndividualCompany::firstOrCreate([
                'applicant_id' => $args['owner_id'],
                'applicant_type' => class_basename(ApplicantIndividual::class),
                'applicant_company_id' => $applicant->id,
                'applicant_individual_company_relation_id' => ($args['owner_relation_id']) ?? $args['owner_relation_id'],
                'applicant_individual_company_position_id' => ($args['owner_position_id']) ?? $args['owner_position_id'],
            ]);
        } catch (\Exception $exception) {
            throw new GraphqlException($exception->getMessage());
        }
    }

    public function sendEmailVerification($_, array $args)
    {
        $applicantCompany = ApplicantCompany::find($args['applicant_company_id']);
        $applicant = ApplicantIndividual::find($applicantCompany->owner_id);
        $company = $applicantCompany->company;

        $verifyToken = $this->verifyService->createVerifyToken($applicant);

        $emailTemplateName = 'Sign Up: Email Confirmation';
        $emailData = [
            'client_name' => $applicantCompany->name,
            'email_confirm_url' => $company->member_verify_url.'/email/verify/'.$verifyToken->token.'/'.$applicantCompany->id,
            'company_name' => $company->name,
        ];
        $emailDTO = TransformerDTO::transform(EmailApplicantCompanyRequestDTO::class, $applicantCompany, $company, $emailTemplateName, $emailData);

        $this->emailService->sendApplicantCompanyEmailByApplicantDto($emailDTO);

        $applicantCompany->email_verification_status_id = ApplicantVerificationStatusEnum::REQUESTED->value;
        $applicantCompany->save();

        event(new ApplicantIndividualSentEmailVerificationEvent($applicant));

        return $applicantCompany;
    }

    public function sendPhoneVerification($_, array $args)
    {
        $applicantCompany = ApplicantCompany::find($args['applicant_company_id']);

        $applicantCompany->phone_verification_status_id = ApplicantVerificationStatusEnum::REQUESTED->value;
        $applicantCompany->save();

        return $applicantCompany;
    }
}
