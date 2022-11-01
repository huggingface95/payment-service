<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ApplicantIndividual;

class ApplicantService
{

    public function getApplicantRequisites(ApplicantIndividual $applicant, Account $account): array
    {
        $bank = $account->paymentBank;
        $applicantCompany = $account->owner->companies->first();

        return [
            'currency' => $account->currencies->code,
            'beneficiary' => $applicantCompany ? $applicantCompany->name : $applicant->fullname,
            'address' => $applicantCompany ? $applicantCompany->address : $applicant->address,
            'country' => $applicantCompany ? $applicantCompany->country->name : $applicant->country->name,
            'iban' => $account->account_number,
            'bank_name' => $bank->name,
            'swift_code' => $bank->bank_code,
            'bank_address' => $bank->address,
            'bank_country' => $bank->country->name,
        ];
    }

}
