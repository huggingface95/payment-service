<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividual;
use App\Models\ApplicantRiskLevelHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ApplicantHistoryRiskLevelMutator extends BaseMutator
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
        $applicantRiskLevelHistory = ApplicantRiskLevelHistory::create($args);
        if (isset($args['risk_level_id'])) {
            $applicant = ApplicantIndividual::where('id', '=', $args['applicant_id'])->first();
            $applicant->update(['applicant_risk_level_id' => $args['risk_level_id']]);
        }

        return $applicantRiskLevelHistory;
    }

}
