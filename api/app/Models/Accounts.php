<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Accounts
 *
 *
 * @property CommissionTemplate $commissionTemplate
 * @property AccountLimit $limits
 * @property AccountReachedLimit $reachedLimits
 * @property ApplicantIndividual | ApplicantCompany $clientable
 */
class Accounts extends BaseModel
{
    use MorphToOne, BelongsToOne;

    const PRIVATE = 'Private';

    const BUSINESS = 'Business';

    public $timestamps = false;

    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'owner_id',
        'account_number',
        'account_type',
        'payment_provider_id',
        'commission_template_id',
        'account_state_id',
        'account_name',
        'is_primary',
        'current_balance',
        'reserved_balance',
        'available_balance',
        'order_reference',
        'company_id',
        'member_id',
        'group_type_id',
        'group_role_id',
        'payment_system_id',
        'client_id',
        'payment_bank_id',
    ];

    public static self $clone;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope(parent::getApplicantIdsByAuthMember()));
    }

    public function load($relations): self
    {
        self::$clone = $this->replicate();

        return parent::load($relations);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /**
     * Get relation currencies
     *
     * @return BelongsTo
     */
    public function currencies(): BelongsTo
    {
        return $this->belongsTo(Currencies::class, 'currency_id', 'id');
    }

    /**
     * Get relation Owner
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'owner_id', 'id');
    }

    /**
     * Get relation Company
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id', 'id');
    }

    /**
     * Get relation Payment Provider
     *
     * @return BelongsTo
     */
    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id', 'id');
    }

    public function paymentSystem(): BelongsTo
    {
        return $this->belongsTo(PaymentSystem::class, 'payment_system_id');
    }

    public function paymentBank(): BelongsTo
    {
        return $this->belongsTo(PaymentBank::class, 'payment_bank_id');
    }

    /**
     * Get relation Payment Provider
     *
     * @return BelongsTo
     */
    public function commissionTemplate(): BelongsTo
    {
        return $this->belongsTo(CommissionTemplate::class, 'commission_template_id', 'id');
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_role_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function setAccountIdAttribute()
    {
        $this->attributes['account_number'] = uniqid();
    }

    public function accountIndividualCompany(): HasOne
    {
        return $this->hasOne(AccountIndividualCompany::class, 'account_id', 'id');
    }

    public function clientable()
    {
        /** @var Accounts $model */
        try {
            $model = self::$clone;
        } catch (\Error $ex) {
            $model = $this;
        }

        if ($this->account_type === self::BUSINESS) {
            return $this->applicantCompany();
        } else {
            return $this->applicantIndividual();
        }

    }

    public function applicantIndividual()
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client', AccountIndividualCompany::class, 'account_id');
    }

    public function applicantCompany()
    {
        return $this->morphedByOne(ApplicantCompany::class, 'client', AccountIndividualCompany::class, 'account_id');
    }

    public function limits(): HasMany
    {
        return $this->hasMany(AccountLimit::class, 'account_id');
    }

    public function reachedLimits(): HasMany
    {
        return $this->hasMany(AccountReachedLimit::class, 'account_id');
    }

    public function accountState(): BelongsTo
    {
        return $this->belongsTo(AccountState::class, 'account_state_id');
    }
}
