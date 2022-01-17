<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividual;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


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
        if (!isset($args['password'])) {
            $password = Str::random(8);
        } else {
            $password =$args['password'];
        }
        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);

        if (isset($args['personal_additional_fields'])) {
            $additionalFields = $args['personal_additional_fields'];
            $args['personal_additional_fields']  = $this->setAdditionalField($additionalFields);
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
        if (isset($args['personal_additional_fields'])) {
            $additionalFields = $args['personal_additional_fields'];
            $args['personal_additional_fields']  = $this->setAdditionalField($additionalFields);
        }
        if (isset($args['profile_additional_fields'])) {
            $additionalFields = $args['profile_additional_fields'];
            $args['profile_additional_fields']  = $this->setAdditionalField($additionalFields);
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
