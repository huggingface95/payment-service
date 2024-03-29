<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyRateHistory extends BaseModel
{
    use BaseObServerTrait;

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'quote_provider_id',
        'currency_src_id',
        'currency_dst_id',
        'rate',
        'created_at',
    ];

    protected $casts = [
        'rate' => 'decimal:5',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function quoteProvider(): BelongsTo
    {
        return $this->belongsTo(QuoteProvider::class, 'quote_provider_id');
    }

    public function currencyFrom(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_src_id');
    }

    public function currencyTo(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_dst_id');
    }
}
