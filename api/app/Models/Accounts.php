<?php

namespace App\Models;


use Ankurk91\Eloquent\MorphToOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


/**
 * Class Accounts
 *
 *
 * @property AccountLimit $limits
 * @property AccountReachedLimit $reachedLimits
 * @property ApplicantIndividual | ApplicantCompany $clientable
 *
 */
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

    public static self $clone;

    public function load($relations): Accounts
    {
        self::$clone = $this->replicate();
        return parent::load($relations);
    }

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

    public function clientable(string $type = null): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        try {$model = self::$clone;}
        catch (\Error $ex){$model = $this;}

        $clientType = AccountIndividualCompany::where('account_id', $model->account_id)->first()->type ?? null;

        if (in_array(ApplicantIndividual::class, [$clientType, $type])){
            return $this->applicantIndividual();
        }
        return $this->applicantCompany();
    }

    public function applicantIndividual(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client', 'account_individuals_companies', 'account_id');
    }

    public function applicantCompany(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client', 'account_individuals_companies', 'account_id');
    }

    public function limits(): HasMany
    {
        return $this->hasMany(AccountLimit::class, 'account_id');
    }

    public function reachedLimits(): HasMany
    {
        return $this->hasMany(AccountReachedLimit::class, 'account_id');
    }
}
