<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ApplicantIndividual;

class ApplicantService
{
    public const DEFAULT_LOGO_PATH = '/img/logo.png';

    public function getApplicantRequisites(ApplicantIndividual $applicant, Account $account): array
    {
        $bankCorrespondent = $account->bankCorrespondentWithCurrency()->first() ?? '';
        $ibanProvider = $account->paymentProviderIban()->first() ?? '';
        $applicantCompany = $account->owner->companies->first();
        $defaultLogoPath = storage_path('pdf').self::DEFAULT_LOGO_PATH;
        $companyLogoPath = $account->company->companySettings->logo->link ?? $defaultLogoPath;
        $country = $applicantCompany ? ($applicantCompany->country->name ?? '') : ($applicant->country->name ?? '');

        return [
            'currency' => $account->currencies->code,
            'beneficiary' => $applicantCompany ? $applicantCompany->name : $applicant->fullname,
            'address' => $applicantCompany ? $applicantCompany->address : $applicant->address,
            'country' => $country,
            'iban' => $bankCorrespondent->account_number ?? '',
            'bank_name' => $ibanProvider->name ?? '',
            'swift_code' => $ibanProvider->swift ?? '',
            'bank_address' => $ibanProvider->provider_address ?? '',
            'bank_country' => $ibanProvider->country->name ?? '',
            'logo_path' => $companyLogoPath,
            'correspondent_bank_name' => $bankCorrespondent->name ?? '',
            'correspondent_bank_swift_code' => $bankCorrespondent->bank_code ?? '',
            'correspondent_bank_address' => $bankCorrespondent->address ?? '',
            'correspondent_bank_account' => $bankCorrespondent->bank_account ?? '',
        ];
    }
}
