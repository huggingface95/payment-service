<?php

namespace App\GraphQL\Queries\Applicant;

use App\DTO\Transfer\Create\Outgoing\Applicant\CreateApplicantTransferOutgoingExchangeDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Repositories\Interfaces\TransferExchangeRepositoryInterface;

class ApplicantCurrencyExchangeRateQuery
{
    public function __construct(protected TransferExchangeRepositoryInterface $repository)
    {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function get($_, array $args): array
    {
        $applicant = auth()->user();
        $account = Account::query()
            ->where('id', $args['account_src_id'])
            ->where('currency_id', $args['currency_src_id'])
            ->where('owner_id', $applicant->id)
            ->orWhere('client_id', $applicant->id)
            ->first() ?? throw new GraphqlException('Account not found', 'use');

        $outgoingDTO = TransformerDTO::transform(CreateApplicantTransferOutgoingExchangeDTO::class, $account, 1, $args);

        return $this->repository->getExchangeRate($outgoingDTO->price_list_fee_id, $args['currency_src_id'], $args['currency_dst_id']);
    }
}
