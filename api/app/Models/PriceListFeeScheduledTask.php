<?php

namespace App\Models;

class PriceListFeeScheduledTask extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'price_list_fee_scheduled_id',
        'account_id',
        'currency_id',
        'date',
    ];
}
