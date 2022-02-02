<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualModules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ApplicantIndividualModulesMutator extends BaseMutator
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

        if (isset($args['applicant_module_id'])) {
            $applicant->modules()->detach();
            $applicant->modules()->attach($args['applicant_module_id']);
        }

        return $applicant;
    }

    public function detach($root, array $args)
    {
        $applicant = ApplicantIndividual::where('id', '=', $args['applicant_individual_id'])->first();
        $applicant->modules()->detach($args['applicant_module_id']);
        return $applicant;
    }

}
