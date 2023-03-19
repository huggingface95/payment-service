<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\PriceListPPFee;
use App\Services\PriceListFeeService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PriceListPPFeesMutator
{
    public function __construct(
        protected PriceListFeeService $priceListFeeService
    ) {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args): PriceListPPFee
    {
        if (isset($args['fee_ranges'])) {
            $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args);
        }

        $priceListPPFee = DB::transaction(function () use ($args) {
            $priceListPPFee = PriceListPPFee::create($args);

            if (isset($args['fees'])) {
                $this->createFeeModes($args, $priceListPPFee);
            }

            return $priceListPPFee;
        });

        return $priceListPPFee;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args): PriceListPPFee
    {
        if (isset($args['fee_ranges'])) {
            $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args);
        }

        $priceListPPFee = PriceListPPFee::find($args['id']);
        if (!$priceListPPFee) {
            throw new GraphqlException('PriceListPPFee not found', 'use', Response::HTTP_NOT_FOUND);
        }

        DB::transaction(function () use ($priceListPPFee, $args) {
            $priceListPPFee->update($args);

            if (isset($args['fees'])) {
                $priceListPPFee->fees()->delete();

                $this->createFeeModes($args, $priceListPPFee);
            }
        });

        return $priceListPPFee;
    }

    private function createFeeModes(array $args, PriceListPPFee $priceListPPFee): void
    {
        $currencies = $args['fees'];
        foreach ($currencies as $currency) {
            foreach ($currency['fee'] as $fees) {
                $priceListPPFee->fees()->create([
                    'price_list_pp_fee_id' => $priceListPPFee->id,
                    'currency_id' => $currency['currency_id'],
                    'fee' => $fees,
                ]);
            }
        }
    }
}
