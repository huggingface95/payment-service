<?php

namespace App\GraphQL\Mutations;

use App\Models\PriceListModeFees;

class PriceListModeFeesMutator
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($_, array $args)
    {

        if (isset($args['currency_ids'])) {
            $currency = $args['currency_ids'];
            unset($args['currency_ids']);
            $priceListModeFees = PriceListModeFees::create($args);
            $priceListModeFees->currencies()->attach($currency);
            return $priceListModeFees;
        } else {
            return PriceListModeFees::create($args);
        }

    }


}
