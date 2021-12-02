<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividual;


class ApplicantMutator extends BaseMutator
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

        if (isset($args['additional_fields'])) {
            $additionalFields = $args['additional_fields'];
            $args['additional_fields']  = $this->setAdditionalField($additionalFields);
        }
        if (isset($args['contacts_additional_fields'])) {
            $contactAdditionalFields = $args['contacts_additional_fields'];
            $args['contacts_additional_fields']  = $this->setAdditionalField($contactAdditionalFields);
        }
        $applicant = ApplicantIndividual::create($args);
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
        $applicant = ApplicantIndividual::find($args['id']);
        if (isset($args['additional_fields'])) {
            $additionalFields = $args['additional_fields'];
            $args['additional_fields']  = $this->setAdditionalField($additionalFields);
        }
        if (isset($args['contacts_additional_fields'])) {
            $contactAdditionalFields = $args['contacts_additional_fields'];
            $args['contacts_additional_fields']  = $this->setAdditionalField($contactAdditionalFields);
        }
        if (isset($args['labels'])) {
            $applicant->labels()->detach($args['labels']);
            $applicant->labels()->attach($args['labels']);
        }
        $applicant->update($args);
        return $applicant;
    }

}
