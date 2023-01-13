<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentProviderIban extends BaseModel
{
    protected $fillable = [
        'name',
        'member_id',
        'currency_id',
        'is_active',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function currency(): HasOne
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }
}
