<?php

namespace App\GraphQL\Mutations;

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

    public function create($root, array $args)
    {
        $args['applicant_module_id'] = 1;
        $applicantModule = ApplicantIndividualModules::create($args);

        if (isset($args['applicant_module_id'])) {
            //$applicantModule->modules()->detach($args['applicant_module_id']);
            $applicantModule->modules()->attach($args['applicant_module_id']);
        }

        return $applicantModule;
    }

}
