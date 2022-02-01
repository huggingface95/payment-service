<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ApplicantCompanyModulesMutator extends BaseMutator
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
        $applicantModule = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();

        if (isset($args['applicant_module_id'])) {
            $applicantModule->modules()->detach();
            $applicantModule->modules()->attach($args['applicant_module_id']);
        }

        return $applicantModule;
    }

    public function detach($root, array $args)
    {
        $applicantModule = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();
        $applicantModule->modules()->detach($args['applicant_module_id']);
        return $applicantModule;
    }

}
