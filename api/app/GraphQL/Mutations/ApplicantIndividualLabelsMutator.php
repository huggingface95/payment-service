<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividual;

class ApplicantIndividualLabelsMutator extends BaseMutator
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function attach($root, array $args)
    {
        $applicant = ApplicantIndividual::where('id', '=', $args['applicant_individual_id'])->first();

        if (isset($args['applicant_individual_label_id'])) {
            $applicant->labels()->detach();
            $applicant->labels()->attach($args['applicant_individual_label_id']);
        }

        return $applicant;
    }

    public function detach($root, array $args)
    {
        $applicant = ApplicantIndividual::where('id', '=', $args['applicant_individual_id'])->first();
        $applicant->labels()->detach($args['applicant_individual_label_id']);

        return $applicant;
    }
}
