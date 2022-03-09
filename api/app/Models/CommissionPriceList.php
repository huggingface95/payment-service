<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommissionPriceList extends BaseModel
{

    use HasFactory;
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
     * @return BelongsTo
     */
    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class,'provider_id','id');
    }

    /**
     * Get relation payment system
     * @return BelongsTo
     */
    public function paymentSystem(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class,'payment_system_id','id');
    }

    /**
     * Get relation commission template
     * @return BelongsTo
     */
    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class,'commission_template_id','id');
    }

    public function scopePaymentProviderName($query, $sort)
    {
        return $query->join('payment_provider','commission_template.payment_provider_id','=','payment_provider.id')->orderBy('payment_provider.name',$sort)->select('commission_template.*');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(PriceListFee::class, 'price_list_id');
    }


}
