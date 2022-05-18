<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\DB;

class Accounts extends BaseModel
{

    public $timestamps = false;

    protected $table="accounts";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id', 'client_id', 'owner_id', 'account_number', 'account_type', 'payment_provider_id', 'commission_template_id', 'account_state', 'account_name', 'is_primary', 'current_balance', 'reserved_balance', 'available_balance'
    ];


    /**
     * Get relation currencies
     * @return BelongsTo
     */
    public function currencies()
    {
        return $this->belongsTo(Currencies::class,'currency_id','id');
    }

    /**
     * Get relation applicant individual
     * @return BelongsTo
     */
    public function applicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'client_id','id');
    }

    /**
     * Get relation Member
     * @return BelongsTo
     */
    public function members()
    {
        return $this->belongsTo(Members::class,'owner_id','id');
    }

    /**
     * Get relation Payment Provider
     * @return BelongsTo
     */
    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class,'payment_provider_id','id');
    }

    public function paymentSystem(): HasOneThrough
    {
        return $this->hasOneThrough(
            PaymentSystem::class,
            PaymentProviderPaymentSystem::class,
            'payment_provider_id',
            'id',
            'payment_provider_id',
            'payment_system_id',
        );
    }

    /**
     * Get relation Payment Provider
     * @return BelongsTo
     */
    public function commissionTemplate()
    {
        return $this->belongsTo(CommissionTemplate::class,'commission_template_id','id');
    }

    public function group()
    {
        return $this->hasOneThrough(GroupRole::class, ApplicantIndividual::class,'member_group_role_id','id','client_id','id');
    }

    public function setAccountIdAttribute()
    {
        $this->attributes['account_number']=uniqid();
    }


}
