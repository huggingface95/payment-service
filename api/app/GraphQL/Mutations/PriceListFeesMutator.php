<?php

namespace App\GraphQL\Mutations;

use App\Models\PriceListFee;
use App\Models\PriceListFeesItem;

class PriceListFeesMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args)
    {
        $priceListFee = PriceListFee::create($args);
        if ($args['fee']) {
            foreach ($args['fee'] as $feeItem) {
                PriceListFeesItem::create([
                    'price_list_fees_id' => $priceListFee->id,
                    'fee_item'=> $feeItem,
                ]);
            }
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
            if (isset($args['fee'])) {
                PriceListFeesItem::where('price_list_fees_id', $args['id'])->delete();
                foreach ($args['fee'] as $feeItem) {
                    PriceListFeesItem::create([
                        'price_list_fees_id' =>  $priceListFee->id,
                        'fee_item'=> $feeItem,
                    ]);
                }
            }
        }

        return  $priceListFee;
    }
}
