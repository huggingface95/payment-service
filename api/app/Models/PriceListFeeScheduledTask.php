<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;

class PriceListFeeScheduledTask extends BaseModel
{
    use BaseObServerTrait;

    public $timestamps = false;

    protected $fillable = [
        'price_list_fee_scheduled_id',
        'account_id',
        'currency_id',
        'date',
    ];
}
