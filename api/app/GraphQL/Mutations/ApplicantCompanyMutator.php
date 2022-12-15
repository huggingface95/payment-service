<?php

namespace App\GraphQL\Mutations;

use App\Enums\ModuleEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividualCompany;
use App\Models\GroupRole;
use App\Models\Groups;

class ApplicantCompanyMutator extends BaseMutator
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function create($root, array $args)
    {
        $args['group_type_id'] = GroupRole::COMPANY;
        $applicantCompany = ApplicantCompany::create($args);

        $args['module_ids'] = array_unique(
            array_merge($args['module_ids'], [(string) ModuleEnum::KYC->value])
        );

        if (isset($args['owner_id']) && isset($args['owner_relation_id']) && isset($args['owner_position_id'])) {
            $this->setOwner($applicantCompany, $args);
        }

        if (isset($args['group_id'])) {
            $applicantCompany->groupRole()->sync([$args['group_id']], true);
        }

        $applicantCompany->modules()->attach($args['module_ids']);

        return $applicantCompany;
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
            $applicant->labels()->detach($args['labels']);
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
                'applicant_type' => class_basename(ApplicantCompany::class),
                'applicant_company_id' => $applicant->id,
                'applicant_individual_company_relation_id' => ($args['owner_relation_id']) ?? $args['owner_relation_id'],
                'applicant_individual_company_position_id' => ($args['owner_position_id']) ?? $args['owner_position_id'],
            ]);
        } catch (\Exception $exception) {
            throw new GraphqlException($exception->getMessage());
        }
    }
}
