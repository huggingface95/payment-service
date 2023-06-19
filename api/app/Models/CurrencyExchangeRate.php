<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;

class CurrencyExchangeRate extends BaseModel
{
    use BaseObServerTrait;

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
        'quote_provider_id',
    ];
}
