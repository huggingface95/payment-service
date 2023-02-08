<?php

namespace App\DTO\Email\Request;

use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\Company;

class EmailApplicantCompanyRequestDTO
{
    public string $emailTemplateName;

    public int $companyId;

    public Account $account;

    public object $data;

    public string $email;

    public static function transform(ApplicantCompany $applicantCompany, Company $company, string $emailTemplateName, array $data): self
    {
        $dto = new self();

        $account = new Account();
        $account->company_id = $company->id;

        $dto->emailTemplateName = $emailTemplateName;
        $dto->companyId = $company->id;
        $dto->account = $account;
        $dto->email = $applicantCompany->email;
        $dto->data = (object) $data;

        return $dto;
    }
}
