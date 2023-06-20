<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyModules;

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
        $applicant = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();

        if (isset($args['module_id'])) {
            $applicant->modules()->detach();
            foreach ($args['module_id'] as $module) {
                ApplicantCompanyModules::query()->create(['module_id'=> $module, 'applicant_company_id' => $args['applicant_company_id']]);
            }
        }

        return $applicant;
    }

    public function detach($root, array $args)
    {
        $applicant = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();
        $applicant->modules()->detach();

        return $applicant;
    }

    public function update($root, array $args)
    {
        $applicant = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();

        if (isset($args['module_id'])) {
            foreach ($args['module_id'] as $module) {
                ApplicantCompanyModules::where([
                    'applicant_company_id' => $args['applicant_company_id'],
                    'module_id' => $module,
                ])->update(['is_active'=>$args['is_active']]);
            }
        }

        return $applicant;
    }
}
