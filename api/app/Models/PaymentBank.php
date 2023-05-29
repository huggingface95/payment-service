<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PaymentBank extends BaseModel
{
    use BaseObServerTrait;
    use HasRelationships;

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
        'account_number',
        'ncs_number',
        'is_active',
    ];

    public function currencies(): HasManyDeep
    {
        return $this->hasManyDeep(
            Currencies::class,
            [PaymentSystem::class, 'payment_system_currencies'],
            [
                'id',
                'payment_system_id',
                'id',
            ],
            [
                'payment_system_id',
                'id',
                'currency_id',
            ],
        );
    }

    public function regions(): HasManyDeep
    {
        return $this->hasManyDeep(
            Region::class,
            [PaymentSystem::class, 'payment_system_regions'],
            [
                'id',
                'payment_system_id',
                'id',
            ],
            [
                'payment_system_id',
                'id',
                'region_id',
            ],
        );
    }


    public function bankCorrespondents(): HasMany
    {
        return $this->hasMany(BankCorrespondent::class, 'payment_bank_id');
    }

    public function currenciesRegions_currencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Currencies::class,
            'payment_bank_currencies_regions',
            'payment_bank_id',
            'currency_id'
        )->distinct('id');
    }

    public function currenciesRegions_regions(): BelongsToMany
    {
        return $this->belongsToMany(
            Region::class,
            'payment_bank_currencies_regions',
            'payment_bank_id',
            'region_id'
        )->distinct('id');
    }

    public function currenciesRegions(): HasMany
    {
        return $this->hasMany(
            PaymentBankCurrencyRegion::class,
            'payment_bank_id',
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
