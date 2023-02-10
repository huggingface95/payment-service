<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantBankingAccess;
use App\Models\ApplicantIndividualCompany;

class ApplicantIndividualCompanyMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args)
    {
        $applicantIndividualCompany = ApplicantIndividualCompany::where('applicant_id', $args['applicant_id'])->where('applicant_company_id', $args['applicant_company_id'])->first();
        if (isset($args['applicant_individual_company_relation_id'])) {
            $args['applicant_individual_company_relation_id'] = $args['applicant_individual_company_relation_id'];
        }
        if (isset($args['applicant_individual_company_position_id'])) {
            $args['applicant_individual_company_position_id'] = $args['applicant_individual_company_position_id'];
        }

        $applicantIndividualCompany->update($args);

        return $applicantIndividualCompany;
    }

    public function delete($_, array $args)
    {
        ApplicantIndividualCompany::where('applicant_id', $args['applicant_id'])->where('applicant_company_id', $args['applicant_company_id'])->delete();
        ApplicantBankingAccess::where('applicant_individual_id', $args['applicant_id'])->where('applicant_company_id', $args['applicant_company_id'])->delete();

        return $args;
    }
}
