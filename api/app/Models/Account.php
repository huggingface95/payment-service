<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use App\Models\Interfaces\BaseModelInterface;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Account
 *
 *
 * @property CommissionTemplate $commissionTemplate
 * @property AccountLimit $limits
 * @property AccountReachedLimit $reachedLimits
 * @property ApplicantIndividual | ApplicantCompany $clientable
 */
class Account extends BaseModel implements BaseModelInterface
{
    use MorphToOne, BelongsToOne;

    const PRIVATE = 'Private';

    const BUSINESS = 'Business';

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
        'payment_bank_id',
    ];

    protected static function booted()
    {
        parent::booted();
//        static::addGlobalScope(new AccountIndividualsCompaniesScope());
        static::addGlobalScope(new ApplicantFilterByMemberScope(parent::getApplicantIdsByAuthMember()));
    }

    public function getClientAccountsAttribute(): array
    {
        return AccountIndividualCompany::query()
            ->leftJoin('account_individuals_companies as aic', function($join)
            {
                $join->on('aic.client_id', '=', 'account_individuals_companies.client_id');
                $join->on('aic.client_type', '=', 'account_individuals_companies.client_type');
            })
            ->leftJoin('accounts as a', 'a.id', '=', 'aic.account_id')
            ->leftJoin('currencies as c', 'c.id', '=', 'a.currency_id')
            ->where('aic.account_id', '<>', $this->id)
            ->where('account_individuals_companies.account_id', '=', $this->id)
            ->select('a.id', 'a.current_balance', 'a.reserved_balance', 'a.available_balance', 'c.code')
            ->get()->toArray();
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

    public function clientable(): MorphTo
    {
        return $this->morphTo('clientable', 'client_type', 'client_id');
    }

    public function clientableAttach(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        if ($this->account_type == self::BUSINESS) {
            return $this->applicantCompany();
        } else {
            return $this->applicantIndividual();
        }
    }

    public function applicantIndividual(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client', AccountIndividualCompany::class, 'account_id');
    }

    public function applicantCompany(): \Ankurk91\Eloquent\Relations\MorphToOne
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

    public static function getAccountFilter($filter): Builder
    {
        return self::query()->join('companies', 'accounts.company_id', '=', 'companies.id')
            ->join('group_role', 'accounts.group_role_id', '=', 'group_role.id')
            ->join('applicant_individual', 'accounts.owner_id', '=', 'applicant_individual.id')
            ->leftJoin('account_individuals_companies', 'accounts.id', '=', 'account_individuals_companies.account_id')
            ->join('payment_provider', 'accounts.payment_provider_id', '=', 'payment_provider.id')
            ->join('commission_template', 'accounts.commission_template_id', '=', 'commission_template.id')
            ->join('members', 'accounts.member_id', '=', 'members.id')
            //->select('accounts.*')
            ->where(function ($q) use ($filter) {
                $q->orWhere('accounts.account_number', 'like', $filter['account_number'] ?? '%')
                    ->orWhere('accounts.account_name', 'like', $filter['account_number'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('companies.id', 'like', $filter['company'] ?? '%')
                    ->orWhere('companies.name', 'like', $filter['company'] ?? '%');
            })
            ->where('accounts.group_type_id', isset($filter['group_type_id']) ? '=' : '!=', $filter['group_type_id'] ?? 0)
            ->where('accounts.is_primary', isset($filter['is_primary']) ? '=' : '!=', $filter['is_primary'] ?? null)
            ->where('accounts.account_type', isset($filter['account_type']) ? '=' : '!=', $filter['account_type'] ?? null)
            ->where('accounts.account_state_id', isset($filter['account_state_id']) ? '=' : '!=', $filter['account_state_id'] ?? 0)
            ->where(function ($q) use ($filter) {
                $q->orWhere('group_role.id', 'like', $filter['group_role'] ?? '%')
                    ->orWhere('group_role.name', 'like', $filter['group_role'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('payment_provider.id', 'like', $filter['payment_provider'] ?? '%')
                    ->orWhere('payment_provider.name', 'like', $filter['payment_provider'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('commission_template.id', 'like', $filter['commission_template'] ?? '%')
                    ->orWhere('commission_template.name', 'like', $filter['commission_template'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('applicant_individual.id', 'like', $filter['owner'] ?? '%')
                    ->orWhere('applicant_individual.fullname', 'like', $filter['owner'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('members.id', 'like', $filter['member'] ?? '%')
                    ->orWhere('members.fullname', 'like', $filter['member'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('account_individuals_companies.client_id', 'like', $filter['client'] ?? '%')
                    ->orWhere('applicant_individual.fullname', 'like', $filter['client'] ?? '%');
            });
    }

    public static function getAccountDetailsFilter($query, $filter)
    {
        return self::join('companies', 'accounts.company_id', '=', 'companies.id')
            ->join('group_role', 'accounts.group_role_id', '=', 'group_role.id')
            ->join('applicant_individual', 'accounts.owner_id', '=', 'applicant_individual.id')
            ->join('payment_provider', 'accounts.payment_provider_id', '=', 'payment_provider.id')
            ->select('accounts.*')
            ->where(function ($q) use ($query) {
                $q->orWhere('accounts.id', 'like', $query['account_name'])
                    ->orWhere('accounts.account_name', 'like', $query['account_name']);
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('companies.id', 'like', $filter['company'] ?? '')
                    ->orWhere('companies.name', 'like', $filter['company'] ?? '');
            })
            ->where('accounts.group_type_id', '=', $filter['group_type_id'] ?? null)
            ->where(function ($q) use ($filter) {
                $q->orWhere('group_role.id', 'like', $filter['group_role'] ?? '%')
                    ->orWhere('group_role.name', 'like', $filter['group_role'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('payment_provider.id', 'like', $filter['payment_provider'] ?? '%')
                    ->orWhere('payment_provider.name', 'like', $filter['payment_provider'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('applicant_individual.id', 'like', $filter['owner'] ?? '%')
                    ->orWhere('applicant_individual.fullname', 'like', $filter['owner'] ?? '%');
            });
    }
}
