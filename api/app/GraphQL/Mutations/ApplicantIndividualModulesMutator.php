<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualModules;

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

        if (isset($args['module_id'])) {
            $applicant->modules()->delete();
            foreach ($args['module_id'] as $module) {
                ApplicantIndividualModules::insert(['module_id'=> $module, 'applicant_individual_id' => $args['applicant_individual_id']]);
            }
        }

        return $applicant;
    }

    public function detach($root, array $args)
    {
        $applicant = ApplicantIndividual::where('id', '=', $args['applicant_individual_id'])->first();
        $applicant->modules()->delete();

        return $applicant;
    }

    public function update($root, array $args)
    {
        $applicant = ApplicantIndividual::where('id', '=', $args['applicant_individual_id'])->first();

        if (isset($args['module_id'])) {
            foreach ($args['module_id'] as $module) {
                ApplicantIndividualModules::where([
                    'applicant_individual_id' => $args['applicant_individual_id'],
                    'module_id' => $module,
                ])->update(['is_active'=>$args['is_active']]);
            }
        }

        return $applicant;
    }
}
