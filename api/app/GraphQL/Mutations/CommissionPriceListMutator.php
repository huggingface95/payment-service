<?php

namespace App\GraphQL\Mutations;

use App\Models\CommissionPriceList;

class CommissionPriceListMutator
{

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function update($_, array $args)
    {
        $commissionPriceList = CommissionPriceList::find($args['id']);

        $commissionPriceList->update($args);
        return $commissionPriceList;
    }
}
