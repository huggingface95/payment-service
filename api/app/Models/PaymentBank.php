<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PaymentBank extends BaseModel
{
    public $timestamps = false;

    protected $table = 'payment_banks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'bank_code',
        'payment_system_code',
        'payment_provider_id',
        'payment_system_id',
        'country_id',
        'swift',
        'is_active',
    ];

    public function bankCorrespondent(): BelongsTo
    {
        return $this->belongsTo(BankCorrespondent::class, 'payment_system_id', 'payment_system_id');
    }

    public function paymentBankCurrencies(): HasMany
    {
        return $this->hasMany(PaymentBankCurrency::class);
    }

    public function paymentBankRegions(): HasMany
    {
        return $this->hasMany(PaymentBankRegion::class);
    }

    public function currencies(): HasManyThrough
    {
        return $this->hasManyThrough(
            Currencies::class,
            PaymentBankCurrency::class,
            'payment_bank_id',
            'id',
            'id',
            'currency_id'
        );
    }

    public function regions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Region::class,
            PaymentBankRegion::class,
            'payment_bank_id',
            'id',
            'id',
            'region_id'
        );
    }

    public function paymentSystem(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id');
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
