<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentProvider extends Model
{

    public $timestamps = false;

    protected $table="payment_provider";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active','description','logo_key','country_id','currency_id'
    ];


    public function getCountryIdAttribute($value)
    {
        return json_decode(str_replace(['{', '}'], ['[', ']'], $value));
    }

    public function setCountryIdAttribute($value) {
        $this->attributes['country_id'] = str_replace(['[', ']'], ['{', '}'], json_encode($value));
    }

    public function getCurrencyIdAttribute($value)
    {
        return json_decode(str_replace(['{', '}'], ['[', ']'], $value));
    }

    public function setCurrencyIdAttribute($value) {
        $this->attributes['currency_id'] = str_replace(['[', ']'], ['{', '}'], json_encode($value));
    }


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
