<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantIndividualCompany;

class MatchedUsersQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getList($_, array $args)
    {
        $applicantIndividualCompanies = ApplicantIndividualCompany::where('applicant_company_id', $args['applicant_company_id']);

        if (isset($args['filter']['column']) && $args['filter']['column'] === 'applicant_type') {
            $applicantIndividualCompanies->where('applicant_type', $args['filter']['value'])->get();
        }

        return $applicantIndividualCompanies->orderBy('applicant_id', 'ASC')->get();
    }
}
