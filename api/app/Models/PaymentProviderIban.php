<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PaymentProviderIban extends BaseModel
{
    protected $fillable = [
        'name',
        'member_id',
        'currency_id',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function currency(): HasOne
    {
        return $this->hasOne(Currencies::class, 'id', 'currency_id');
    }

    public function projectApiSettings(): MorphMany
    {
        return $this->morphMany(ProjectApiSetting::class, 'provider');
    }
}
