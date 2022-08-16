<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @method static findOrFail(int $providerId)
 */
class PaymentProvider extends BaseModel
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'payment_provider';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'description', 'logo_key', 'company_id',
    ];

    /**
     * Get relation payment systems
     *
     * @return BelongsToMany
     */
    public function paymentSystems(): BelongsToMany
    {
        return $this->belongsToMany(PaymentSystem::class, 'payment_provider_payment_system', 'payment_provider_id', 'payment_system_id');
    }

    public function commissionPriceList(): HasOne
    {
        return $this->hasOne(CommissionPriceList::class, 'provider_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function scopePaymentProviderCountry($query, $countryId)
    {
        $countries = implode(',', $countryId);

        return $query->where('country_id', '&&', DB::raw('ARRAY['.$countries.']::integer[]'));
    }

    public function scopePaymentProviderCurrency($query, $currencyId)
    {
        $currencies = implode(',', $currencyId);

        return $query->where('country_id', '&&', DB::raw('ARRAY['.$currencies.']::integer[]'));
    }
}
