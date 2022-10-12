<?php

namespace App\DTO\Email\Request;

use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;

class EmailVerificationRequestDTO
{
    public string $emailTemplateName = 'Sign Up: Email Confirmation';
    public Account $account;
    public object $data;
    public string $email;

    public static function transform(ApplicantIndividual $applicant, string $token, ?ApplicantCompany $applicantCompany): self
    {
        $dto = new self();

        $account = new Account;
        $account->company_id = 21;
        
        $dto->account = $account;
        $dto->email = $applicant->email;

        $data = [
            'client_name' => $applicant->first_name,
            'email_confirm_url' => config('app.url_frontend') . '/email/verify/' . $token,
            'member_company_name' => $applicantCompany->name ?? ''
        ];

        $dto->data = (object) $data;
        
        return $dto;
    }

}
