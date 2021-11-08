<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividualCompany;

class ApplicantIndividualCompanyMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($_, array $args)
    {
        ApplicantIndividualCompany::where('applicant_individual_id',$args['applicant_individual_id'])->where('applicant_company_id',$args['applicant_company_id'])->delete();
        return $args;
    }
}
