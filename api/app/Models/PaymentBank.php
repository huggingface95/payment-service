<?php

namespace App\Models;

use App\GraphQL\Mutations\Traits\OptimizationCurrencyRegionTrait;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PaymentBank extends BaseModel
{
    use BaseObServerTrait;
    use HasRelationships;
    use OptimizationCurrencyRegionTrait;

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

    public function getCurrenciesAndRegionsAttribute(): array
    {
        $currenciesRegions = $this->currenciesRegions()->with('currency', 'region')->get()->groupBy('currency_id')->map(function ($records) {
            return ['regions' => $records->pluck('region'), 'currency' => $records->pluck('currency')->first()];
        });

        return $this->optimizeCurrencyRegionResponse($currenciesRegions);
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Currencies::class,
            'payment_bank_currencies_regions',
            'payment_bank_id',
            'currency_id'
        )->distinct('id');
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(
            Region::class,
            'payment_bank_currencies_regions',
            'payment_bank_id',
            'region_id'
        )->distinct('id');
    }

    public function bankCorrespondents(): HasMany
    {
        return $this->hasMany(BankCorrespondent::class, 'payment_bank_id');
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

    public function company(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations($this->paymentProvider(), (new PaymentProvider())->company());
    }

    public function countryRegion(): HasOneThrough
    {
        return $this->hasOneThrough(
            RegionCountry::class,
            PaymentBankCurrencyRegion::class,
            'payment_bank_id',
            'region_id',
            'id',
            'region_id'
        );
    }

}
