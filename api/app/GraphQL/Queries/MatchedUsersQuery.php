<?php

namespace App\GraphQL\Queries;

use App\Models\ApplicantBankingAccess;
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

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getApplicantIndividuals($_, array $args)
    {
        $applicantIndividualCompanies = ApplicantIndividualCompany::query();
        $applicantBankingAccess = ApplicantBankingAccess::query()->pluck('applicant_individual_id')->toArray();

        if (isset($args['applicant_company_id'])) {
            $applicantIndividualCompanies->where('applicant_company_id', $args['applicant_company_id']);
        }

        return $applicantIndividualCompanies->whereNotIn('applicant_id', $applicantBankingAccess)->where('applicant_type', 'ApplicantIndividual')->orderBy('applicant_id', 'ASC')->get();
    }
}
