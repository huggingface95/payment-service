<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantModuleActivity;

class ApplicantModuleActivityMutator
{
    /**
     * @param  $root
     * @param  array  $args
     * @return mixed
     */
    public function update($root, array $args): ApplicantModuleActivity
    {
        $model = ApplicantModuleActivity::updateOrCreate([
            'module_id' => $args['module_id'],
            'applicant_id' => $args['applicant_id'],
            'applicant_type' => $args['applicant_type'],
        ], [
            'is_active' => $args['is_active'],
        ]);

        return $model;
    }
}
