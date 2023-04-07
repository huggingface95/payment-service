<?php

namespace App\Models;

use App\GraphQL\Mutations\Traits\OptimizationCurrencyRegionTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankCorrespondent extends BaseModel
{
    use OptimizationCurrencyRegionTrait;
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
            'bank_correspondent_currencies_regions',
            'bank_correspondent_id',
            'currency_id'
        )->distinct('id');
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(
            Region::class,
            'bank_correspondent_currencies_regions',
            'bank_correspondent_id',
            'region_id'
        )->distinct('id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function paymentBank(): BelongsTo
    {
        return $this->belongsTo(PaymentBank::class, 'payment_bank_id');
    }

    public function currenciesRegions(): HasMany
    {
        return $this->hasMany(
            BankCorrespondentCurrencyRegion::class,
            'bank_correspondent_id',
        );
    }
}
