<?php

namespace App\GraphQL\Queries;

use App\DTO\GraphQLResponse\ApplicantLinkedCompanyResponse;
use App\DTO\TransformerDTO;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualCompany;

final class ApplicantLinkedCompanies
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): \Illuminate\Database\Eloquent\Collection|array|\Illuminate\Support\Collection
    {
        $applicantLinkedCompaniesResponseList = [];
        /** @var ApplicantIndividual $individual */
        if ($individual = ApplicantIndividual::query()->find($args['applicant_individual_id'])) {
            $applicantLinkedCompaniesResponseList = $individual->applicantIndividualCompanies()
                ->get()
                ->map(function (ApplicantIndividualCompany $applicantIndividualCompany){
                    return TransformerDTO::transform(ApplicantLinkedCompanyResponse::class, $applicantIndividualCompany);
                });
        }

        return $applicantLinkedCompaniesResponseList;
    }
}
