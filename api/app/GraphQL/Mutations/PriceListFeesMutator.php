<?php

namespace App\GraphQL\Mutations;

use App\Models\PriceListFee;
use App\Services\PriceListFeeService;

class PriceListFeesMutator
{
    public function __construct(protected PriceListFeeService $priceListFeeService)
    {
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args)
    {
        $args['fees'] = $this->priceListFeeService->convertFeeRangesToFees($args['fee_ranges']);

        $priceListFee = PriceListFee::create($args);

        if (isset($args['fees'])) {
            $this->createFeeModes($args['fees'], $priceListFee);
        }

        return $priceListFee;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args)
    {
        $priceListFee = PriceListFee::find($args['id']);

        if ($priceListFee) {
            $priceListFee->update($args);

            if (isset($args['commission_price_list'][0])) {
                $field = $args['commission_price_list'][0];

                $priceListFee->priceList()->update([
                    'provider_id' => $field['payment_provider_id'],
                    'payment_system_id' => $field['payment_system_id'],
                    'commission_template_id' => $field['commission_template_id'],
                    'company_id' => $field['company_id'],
                ]);
            }

            if (isset($args['fees'])) {
                $priceListFee->fees()->delete();

                $this->createFeeModes($args['fees'], $priceListFee);
            }
        }

        return $priceListFee;
    }

    private function createFeeModes(array $currencies, PriceListFee $priceListFee): void
    {
        foreach ($currencies as $currency) {
            foreach ($currency['fee'] as $fees) {
                $priceListFee->fees()->create([
                    'price_list_fee_id' => $priceListFee->id,
                    'currency_id' => $currency['currency_id'],
                    'fee' => $fees,
                ]);
            }
        }
    }
}
