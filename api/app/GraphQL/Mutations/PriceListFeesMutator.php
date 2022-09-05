<?php

namespace App\GraphQL\Mutations;

use App\Models\PriceListFee;

class PriceListFeesMutator
{

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args)
    {
        $priceListFee = PriceListFee::create($args);

//        if (isset($args['fees'])) {
//            $this->createFeeModes($args['fees'], $priceListFee);
//        }

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

//            if (isset($args['fees'])) {
//                $priceListFee->fees()->delete();
//
//                $this->createFeeModes($args['fees'], $priceListFee);
//            }
        }

        return $priceListFee;
    }

    private function createFeeModes(array $fees, PriceListFee $priceListFee): void
    {
        foreach ($fees as $feeItem) {
            foreach ($feeItem[0]['fee_modes'] as $feeMode) {
                $priceListFee->fees()->create([
                    'fee_mode_id' => $feeMode['fee_mode_id'],
                    'fee' => $feeMode['fee'],
                    'fee_from' => $feeMode['fee_from'] ?? null,
                    'fee_to' => $feeMode['fee_to'] ?? null,
                    'currency_id' => $feeItem[0]['currency_id'],
                ]);
            }
        }
    }
}
