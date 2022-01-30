<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantCompany;


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

        if (isset($args['info_additional_fields'])) {
            $args['info_additional_fields']  = $this->setAdditionalField($args['info_additional_fields']);
        }
        if (isset($args['contacts_additional_fields'])) {
            $args['contacts_additional_fields']  = $this->setAdditionalField($args['contacts_additional_fields']);
        }

        $applicant = ApplicantCompany::create($args);

        if (isset($args['labels'])) {
            $applicant->labels()->detach($args['labels']);
            $applicant->labels()->attach($args['labels']);
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
        $applicant = ApplicantCompany::find($args['id']);
        if (isset($args['info_additional_fields'])) {
            $args['info_additional_fields']  = $this->setAdditionalField($args['info_additional_fields']);
        }
        if (isset($args['contacts_additional_fields'])) {
            $args['contacts_additional_fields']  = $this->setAdditionalField($args['contacts_additional_fields']);
        }

        if (isset($args['labels'])) {
            $applicant->labels()->detach($args['labels']);
            $applicant->labels()->attach($args['labels']);
        }

        $applicant->update($args);
        return $applicant;
    }

}
