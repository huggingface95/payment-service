<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantCompany;
use App\Models\ApplicantCompanyRiskLevelHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ApplicantCompanyRiskLevelHistoryMutator extends BaseMutator
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
        $applicantCompanyRiskLevelHistory = ApplicantCompanyRiskLevelHistory::create($args);
        if (isset($args['risk_level_id'])) {
            $applicantCompany = ApplicantCompany::where('id', '=', $args['applicant_company_id'])->first();
            $applicantCompany->update(['applicant_company_risk_level_id' => $args['risk_level_id']]);
        }

        return $applicantCompanyRiskLevelHistory;
    }

}
