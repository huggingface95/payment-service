<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantCompany;

class ApplicantCompanyModulesQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getList($_, array $args)
    {
        $modules = [];

        $applicantModules = ApplicantCompany::findOrFail($args['applicant_company_id'])->modules;

        foreach ($applicantModules as $module) {
            $modules[] = [
                'id' => $module->id,
                'name' => $module->name,
                'is_active' => $module->pivot->is_active,
            ];
        }

        return $modules;
    }
}
