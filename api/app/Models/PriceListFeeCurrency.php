<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceListFeeCurrency extends BaseModel
{
    use BaseObServerTrait;

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

    public function feeDestinationCurrency(): HasMany
    {
        return $this->hasMany(PriceListFeeDestinationCurrency::class, 'price_list_fee_currency_id');
    }
}
