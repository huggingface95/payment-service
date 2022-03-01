<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PaymentProvider extends BaseModel
{

    public $timestamps = false;

    protected $table="payment_provider";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active','description','logo_key'
    ];


    /**
     * Get relation currencies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currencies()
    {
        return $this->belongsToMany(Currencies::class,'payment_provider_currency','payment_provider_id','currency_id');
    }

    /**
     * Get relation countries
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class,'payment_provider_country','payment_provider_id','country_id');
    }

    /**
     * Get relation payment systems
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function paymentSystems()
    {
        return $this->belongsToMany(PaymentSystem::class,'payment_provider_payment_system','payment_provider_id','payment_system_id');
    }

    public function scopePaymentProviderCountry($query, $countryId)
    {        
		$countries = implode(',', $countryId);
        return $query->where('country_id', '&&', DB::raw('ARRAY[' . $countries . ']::integer[]'));
    }

    public function scopePaymentProviderCurrency($query, $currencyId)
    {
        $currencies = implode(',', $currencyId);
        return $query->where('country_id', '&&', DB::raw('ARRAY[' . $currencies . ']::integer[]'));
    }

}
