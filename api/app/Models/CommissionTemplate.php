<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;

class CommissionTemplate extends BaseModel
{

    public $timestamps = false;

    protected $table="commission_template";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active','description','payment_provider_id','country_id','currency_id','commission_template_limit_id'
    ];


    public function getCountryIdAttribute($value)
    {
        return $this->getArrayAttribute($value);
    }

    public function setCountryIdAttribute($value) {
        $this->attributes['country_id'] = $this->setArrayAttribute($value);
    }

    public function getCurrencyIdAttribute($value)
    {
        return $this->getArrayAttribute($value);
    }

    public function setCurrencyIdAttribute($value) {
        $this->attributes['currency_id'] = $this->setArrayAttribute($value);
    }

    public function businessActivity()
    {
        return $this->belongsToMany(BusinessActivity::class,'commission_template_business_activity','commission_template_id','business_activity_id');
    }

    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class,'payment_provider_id','id');
    }


}
