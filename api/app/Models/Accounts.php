<?php

namespace App\Models;


use Ankurk91\Eloquent\MorphToOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class Accounts extends BaseModel
{

    use MorphToOne;

    public $timestamps = false;

    protected $table = "accounts";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id', 'owner_id', 'account_number', 'account_type', 'payment_provider_id', 'commission_template_id', 'account_state', 'account_name', 'is_primary', 'current_balance', 'reserved_balance', 'available_balance', 'order_reference'
    ];

    public function getClientAttribute()
    {
        return $this->clientable->client ?? null;
    }

    /**
     * Get relation currencies
     * @return BelongsTo
     */
    public function currencies(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id', 'id');
    }

    /**
     * Get relation Member
     * @return BelongsTo
     */
    public function members(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'owner_id', 'id');
    }

    /**
     * Get relation Payment Provider
     * @return BelongsTo
     */
    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id', 'id');
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
    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id', 'id');
    }

    public function group(): HasOneThrough
    {
        return $this->hasOneThrough(GroupRole::class, ApplicantIndividual::class, 'member_group_role_id', 'id', 'client_id', 'id');
    }

    public function setAccountIdAttribute()
    {
        $this->attributes['account_number'] = uniqid();
    }

    public function clientable(): HasOne
    {
        return $this->hasOne(AccountIndividualCompany::class, 'account_id');
    }

    public function applicantIndividual(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client');
    }

    public function applicantCompany(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client');
    }

}
