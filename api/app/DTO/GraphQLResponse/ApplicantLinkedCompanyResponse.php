<?php

namespace App\DTO\GraphQLResponse;

use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividualCompany;
use App\Models\ApplicantIndividualCompanyPosition;
use App\Models\ApplicantIndividualCompanyRelation;

class ApplicantLinkedCompanyResponse
{
    public ?ApplicantCompany $company;
    public ?ApplicantIndividualCompanyPosition $company_position;
    public ?ApplicantIndividualCompanyRelation $company_relation;

    public static function transform(ApplicantIndividualCompany $applicantIndividualCompany): self
    {
        $dto = new self();
        $dto->company = $applicantIndividualCompany->ApplicantCompany()->first();
        $dto->company_position = $applicantIndividualCompany->ApplicantIndividualCompanyPosition()->first();
        $dto->company_relation = $applicantIndividualCompany->ApplicantIndividualCompanyRelation()->first();

        return $dto;
    }
}
