<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BankCorrespondent extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'bank_code',
        'bank_account',
        'payment_system_id',
        'country_id',
        'swift',
        'account_number',
        'ncs_number',
        'is_active',
        'payment_bank_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function bankCorrespondentCurrencies(): HasMany
    {
        return $this->hasMany(BankCorrespondentCurrency::class);
    }

    public function bankCorrespondentRegions(): HasMany
    {
        return $this->hasMany(BankCorrespondentRegion::class);
    }

    public function currencies(): HasManyThrough
    {
        return $this->hasManyThrough(
            Currencies::class,
            BankCorrespondentCurrency::class,
            'bank_correspondent_id',
            'id',
            'id',
            'currency_id'
        );
    }

    public function regions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Region::class,
            BankCorrespondentRegion::class,
            'bank_correspondent_id',
            'id',
            'id',
            'region_id'
        );
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function paymentBank(): BelongsTo
    {
        return $this->belongsTo(PaymentBank::class, 'payment_bank_id');
    }
}
