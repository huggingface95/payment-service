<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListPPFeeCurrency extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'price_list_pp_fee_currency';

    protected $fillable = [
        'price_list_pp_fee_id',
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

    public function priceListPPFee(): BelongsTo
    {
        return $this->belongsTo(PriceListPPFee::class, 'price_list_pp_fee_id', 'id');
    }
}
