<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;

class PriceListFeeCurrency extends BaseModel
{
    protected $table = 'price_list_fee_currency';

    protected $fillable = [
        'price_list_fee_id',
        'currency_id',
        'fee',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fee' => AsCollection::class,
    ];
}
