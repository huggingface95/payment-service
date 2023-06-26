<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CurrencyExchangeRate
 *
 * @property QuoteProvider $quoteProvider
 */
class CurrencyExchangeRate extends BaseModel
{
    use BaseObServerTrait;

    const CREATED_AT = null;

    public $timestamps = true;

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

    protected $casts = [
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function quoteProvider(): BelongsTo
    {
        return $this->belongsTo(QuoteProvider::class, 'quote_provider_id');
    }

    public function currencyFrom(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_from_id');
    }

    public function currencyTo(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_to_id');
    }

}
