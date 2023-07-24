<?php

namespace App\GraphQL\Queries\Applicant;

use App\DTO\Transfer\Create\Outgoing\Applicant\CreateApplicantTransferOutgoingExchangeDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\CurrencyExchangeRate;
use App\Models\PriceListFee;
use Illuminate\Http\Response;

class ApplicantCurrencyExchangeRateQuery
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args): CurrencyExchangeRate
    {
        $applicant = auth()->user();
        $account = Account::query()
            ->where('id', $args['account_src_id'])
            ->where('currency_id', $args['currency_src_id'])
            ->where('owner_id', $applicant->id)
            ->orWhere('client_id', $applicant->id)
            ->first() ?? throw new GraphqlException('Account not found', 'use');

        $outgoingDTO = TransformerDTO::transform(CreateApplicantTransferOutgoingExchangeDTO::class, $account, 1, $args);
        $quoteProviderId = PriceListFee::query()
            ->where('id', $outgoingDTO->price_list_fee_id)
            ->first()?->quote_provider_id ?? throw new GraphqlException('Quote provider not found', 'use', Response::HTTP_NOT_FOUND);

        return CurrencyExchangeRate::query()
            ->where('quote_provider_id', $quoteProviderId)
            ->where('currency_src_id', $args['currency_src_id'])
            ->where('currency_dst_id', $args['currency_dst_id'])
            ->first() ?? throw new GraphqlException('Currency exchange rate not found', 'use', Response::HTTP_NOT_FOUND);
    }
}
