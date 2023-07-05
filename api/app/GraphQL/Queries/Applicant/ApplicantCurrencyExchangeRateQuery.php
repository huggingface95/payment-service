<?php

namespace App\GraphQL\Queries\Applicant;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\CurrencyExchangeRate;

class ApplicantCurrencyExchangeRateQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args): CurrencyExchangeRate
    {
        $applicant = auth()->user();

        $applicantType = $applicant instanceof ApplicantIndividual ? ApplicantTypeEnum::INDIVIDUAL->toString() : ApplicantTypeEnum::COMPANY->toString();

        $quoteProviderId = $applicant->project->projectSettings->where('applicant_type', $applicantType)->first()->quote_provider_id;
        if (!$quoteProviderId) {
            throw new GraphqlException('Quote provider not found');
        }

        return CurrencyExchangeRate::query()
            ->where('quote_provider_id', $quoteProviderId)
            ->where('currency_src_id', $args['currency_src_id'])
            ->where('currency_dst_id', $args['currency_dst_id'])
            ->first() ?? throw new GraphqlException('Currency exchange rate not found', 'use', 404);
    }
}
