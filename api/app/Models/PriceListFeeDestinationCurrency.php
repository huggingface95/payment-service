<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;

class PriceListFeeDestinationCurrency extends BaseModel
{
    use BaseObServerTrait;

    protected $fillable = [
        'price_list_fee_currency_id',
        'currency_id',
    ];

    public $timestamps = false;
}
