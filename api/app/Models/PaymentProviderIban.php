<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PaymentProviderIban extends BaseModel
{
    protected $fillable = [
        'name',
        'swift',
        'sort_code',
        'provider_address',
        'about',
        'member_id',
        'currency_id',
        'logo_id',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function currency(): HasOne
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'logo_id');
    }

    public function projectApiSettings(): MorphMany
    {
        return $this->morphMany(ProjectApiSetting::class, 'provider');
    }
}
