<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;

class PriceListQpFeeDestinationCurrency extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'price_list_qp_fee_destination_currencies';

    protected $fillable = [
        'price_list_qp_fee_currency_id',
        'currency_id',
    ];

    public $timestamps = false;
}
