<?php

namespace App\DTO\Email\Request;

use App\Models\Account;
use App\Models\ApplicantIndividual;
use App\Models\Company;

class EmailApplicantRequestDTO
{
    public string $emailTemplateName;
    public Account $account;
    public object $data;
    public string $email;

    public static function transform(ApplicantIndividual $applicant, Company $company, string $emailTemplateName, array $data): self
    {
        $dto = new self();

        $account = new Account;
        $account->company_id = $company->id;

        $dto->emailTemplateName = $emailTemplateName;
        $dto->account = $account;
        $dto->email = $applicant->email;
        $dto->data = (object) $data;

        return $dto;
    }

}
