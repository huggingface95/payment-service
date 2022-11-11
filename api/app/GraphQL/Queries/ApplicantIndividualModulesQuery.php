<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantIndividual;

class ApplicantIndividualModulesQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getList($_, array $args)
    {
        $modules = [];

        $applicantModules = ApplicantIndividual::findOrFail($args['applicant_individual_id'])->modules;

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
