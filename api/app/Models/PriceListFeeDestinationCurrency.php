<?php

namespace App\Models;

class PriceListFeeDestinationCurrency extends BaseModel
{
    protected $fillable = [
        'price_list_fee_currency_id',
        'currency_id',
    ];

    public $timestamps = false;
}
