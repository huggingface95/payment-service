<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PaymentProviderIban extends BaseModel
{
    use BaseObServerTrait;

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
        'bank_country_id',
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

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'bank_country_id');
    }

    public function projectApiSettings(): MorphMany
    {
        return $this->morphMany(ProjectApiSetting::class, 'provider');
    }
}
