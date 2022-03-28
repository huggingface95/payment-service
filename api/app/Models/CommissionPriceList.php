<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class CommissionPriceList extends BaseModel
{

    use HasFactory;

    public $timestamps = false;

    protected $table = "commission_price_list";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'provider_id', 'payment_system_id', 'commission_template_id'
    ];

    /**
     * Get relation payment provider
     * @return BelongsTo
     */
    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'provider_id', 'id');
    }

    /**
     * Get relation payment system
     * @return BelongsTo
     */
    public function paymentSystem(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id', 'id');
    }

    /**
     * Get relation commission template
     * @return BelongsTo
     */
    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id', 'id');
    }

    public function scopePaymentProviderName(Builder $query, $sort): Builder
    {
        return $query->leftJoin(
            DB::raw('(SELECT id, name as payment_provider_name FROM "payment_provider") p'),
            function($join)
            {
                $join->on('p.id', '=','commission_price_list.provider_id');
            })
            ->orderBy('p.payment_provider_name', $sort)
            ->selectRaw('commission_price_list.*');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(PriceListFee::class, 'price_list_id');
    }

    public function owner(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantIndividual::class, 'accounts', 'commission_template_id', 'client_id', 'commission_template_id', 'id');
    }

    public function company(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantCompany::class, 'accounts', 'commission_template_id', 'client_id', 'commission_template_id', 'owner_id');
    }

    public function account(): HasOneThrough
    {
        return $this->hasOneThrough(Accounts::class, CommissionTemplate::class, 'id', 'commission_template_id', 'commission_template_id', 'id',);
    }

}
