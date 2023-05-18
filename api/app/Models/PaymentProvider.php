<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * @PaymentProvider
 *
 * @property int id
 *
 * @method static findOrFail(int $providerId)
 */
class PaymentProvider extends BaseModel
{
    use SoftDeletes;
    use HasRelationships;

    public $timestamps = false;

    protected $table = 'payment_provider';

    public const NAME_INTERNAL = 'Internal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'description', 'logo_id', 'company_id',
    ];

    public function paymentSystems(): HasMany
    {
        return $this->hasMany(PaymentSystem::class, 'payment_provider_id');
    }

    public function commissionPriceList(): HasOne
    {
        return $this->hasOne(CommissionPriceList::class, 'provider_id', 'id');
    }

    public function commissionTemplate(): HasOne
    {
        return $this->hasOne(CommissionTemplate::class, 'payment_provider_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function account(): HasMany
    {
        return $this->hasMany(Account::class, 'payment_provider_id');
    }

    public function scopePaymentProviderCountry($query, $countryId)
    {
        $countries = implode(',', $countryId);

        return $query->where('country_id', '&&', DB::raw('ARRAY['.$countries.']::integer[]'));
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'logo_id');
    }

    public function projectApiSettings(): MorphMany
    {
        return $this->morphMany(ProjectApiSetting::class, 'provider');
    }

    public function countries(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
    {
        return $this->hasManyDeep(
            Country::class,
            [Region::class, RegionCountry::class],
            [
                'company_id',
                'region_id',
                'id',
            ],
            [
                'company_id',
                'id',
                'country_id',
            ],
        );
    }

    public function currencies(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
    {
        return $this->hasManyDeep(
            Currencies::class,
            [PaymentSystem::class, 'payment_system_currencies'],
            [
                'payment_provider_id',
                'payment_system_id',
                'id',
            ],
            [
                'id',
                'id',
                'currency_id',
            ],
        );
    }

    public function scopePaymentProviderCurrency($query, $currencyId)
    {
        $currencies = implode(',', $currencyId);

        return $query->where('country_id', '&&', DB::raw('ARRAY['.$currencies.']::integer[]'));
    }
}
