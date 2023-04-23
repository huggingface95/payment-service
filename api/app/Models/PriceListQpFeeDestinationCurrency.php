<?php

namespace App\Models;

class PriceListQpFeeDestinationCurrency extends BaseModel
{
    protected $table = 'price_list_qp_fee_destination_currencies';

    protected $fillable = [
        'price_list_qp_fee_currency_id',
        'currency_id',
    ];

    public $timestamps = false;
}
