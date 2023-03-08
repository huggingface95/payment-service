<?php

namespace App\Models;

class CurrencyExchangeRate extends BaseModel
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_from_id',
        'currency_to_id',
        'rate',
    ];
}
