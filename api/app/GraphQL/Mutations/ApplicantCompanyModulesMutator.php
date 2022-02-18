<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyModules;
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
            $applicantModule->modules()->delete();
            foreach ($args['applicant_module_id'] as $module) {
                ApplicantCompanyModules::insert(['applicant_module_id'=> $module, 'applicant_company_id' => $args['applicant_company_id']]);
            }
        }

        return $applicantModule;
    }

    public function detach($root, array $args)
    {
        $applicantModule = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();
        $applicantModule->modules()->delete();
        return $applicantModule;
    }

    public function update($root, array $args)
    {
        $applicantModule = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();

        if (isset($args['applicant_module_id'])) {
            foreach ($args['applicant_module_id'] as $module) {
                ApplicantCompanyModules::where([
                    'applicant_company_id' => $args['applicant_company_id'],
                    'applicant_module_id' => $module
                ])->update(['is_active'=>$args['is_active']]);
            }

        }

        return $applicantModule;
    }

}
