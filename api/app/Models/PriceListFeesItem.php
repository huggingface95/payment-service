<?php

namespace App\Models;

class PriceListFeesItem extends BaseModel
{
    protected $table = 'price_list_fees_item';

    protected $fillable = [
        'price_list_fees_id',
        'fee_mode_id',
        'fee',
        'fee_from',
        'fee_to',
        'currency_id',
    ];

    public $timestamps = false;
}
