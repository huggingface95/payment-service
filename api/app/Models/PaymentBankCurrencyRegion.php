<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PaymentBankCurrencyRegion extends Pivot
{
    protected $table = 'payment_bank_currencies_regions';

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currencies::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
