<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;

class CommissionPriceList extends BaseModel
{

    public $timestamps = false;

    protected $table="commission_price_list";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'provider_id','payment_system_id','commission_template_id'
    ];

    /**
     * Get relation payment provider
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class,'provider_id','id');
    }

    /**
     * Get relation payment system
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentSystem()
    {
        return $this->belongsTo(PaymentSystem::class,'payment_system_id','id');
    }

    /**
     * Get relation commission template
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commissionTemplate()
    {
        return $this->belongsTo(CommissionTemplate::class,'commission_template_id','id');
    }


    public function scopePaymentProviderName($query, $sort)
    {
        return $query->join('payment_provider','commission_template.payment_provider_id','=','payment_provider.id')->orderBy('payment_provider.name',$sort)->select('commission_template.*');
    }


}
