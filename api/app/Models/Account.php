<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use App\Enums\ModuleEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Builders\AccountBuilder;
use App\Models\Interfaces\BaseModelInterface;
use App\Models\Interfaces\CustomObServerInterface;
use App\Models\Interfaces\HistoryInterface;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Traits\BaseObServerTrait;
use App\Observers\AccountObserver;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Account
 *
 * @property int id
 * @property string account_number
 * @property int account_state_id
 * @property int group_type_id
 * @property int company_id
 * @property int parent_id
 * @property float min_limit_balance
 * @property float max_limit_balance
 * @property float current_balance
 * @property AccountState $accountState
 * @property Members $member
 * @property Groups $group
 * @property CommissionTemplate $commissionTemplate
 * @property AccountLimit $limits
 * @property AccountReachedLimit $reachedLimits
 * @property ApplicantIndividual | ApplicantCompany clientable
 * @property Currencies currencies
 * @property Account parent
 * @property Company $company
 * @property Collection children
 *
 * @method static find(int $id)
 * @method static findOrFail(int $id)
 */
class Account extends BaseModel implements BaseModelInterface, CustomObServerInterface, HistoryInterface
{
    use MorphToOne;
    use BelongsToOne;
    use BaseObServerTrait;
    use SoftDeletes;

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
        'iban_provider_id',
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
        'payment_bank_id',
        'client_id',
        'client_type',
        'is_show',
        'entity_id',
        'min_limit_balance',
        'max_limit_balance',
        'project_id',
        'parent_id',
        'client_id',
        'client_type',
    ];

    protected $casts = [
        'min_limit_balance' => 'decimal:5',
        'max_limit_balance' => 'decimal:5',
        'current_balance' => 'decimal:5',
        'reserved_balance' => 'decimal:5',
        'available_balance' => 'decimal:5',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'activated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'last_charge_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'deleted_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected $appends = [
        'total_transactions',
        'total_pending_transactions',
        'last_transaction_at',
        'alias',
        'is_active',
    ];

    private static array $cached_transferIncomings = [];
    private static array $cached_transferOutgoings = [];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    public function newEloquentBuilder($builder): AccountBuilder
    {
        return new AccountBuilder($builder);
    }

    public function setAccountStateIdAttribute($value)
    {
        if (isset($this->attributes['account_state_id']) && $value != $this->attributes['account_state_id'] && $value == AccountState::ACTIVE) {
            $this->attributes['activated_at'] = Carbon::now();
        }

        $this->attributes['account_state_id'] = $value;
    }

    public function getIsActiveAttribute()
    {
        return $this->account_state_id == AccountState::ACTIVE;
    }

    public function getAliasAttribute(): bool
    {
        return !$this->isParent();
    }

    public function getClientAccountsAttribute(): array
    {
        return self::query()->with('currencies')
            ->join('accounts as a', function ($join) {
                $join->on('a.client_id', '=', 'accounts.client_id');
                $join->on('a.client_type', '=', 'accounts.client_type');
            })
            ->where('accounts.id', '=', $this->id)
            ->select('a.id', 'a.current_balance', 'a.reserved_balance', 'a.available_balance', 'a.currency_id', 'a.min_limit_balance', 'a.max_limit_balance')
            ->get()
            ->map(function ($account) {
                $account->relations['currency'] = $account->relations['currencies'];
                unset($account->currency_id);
                unset($account->relations['currencies']);

                return $account;
            })
            ->toArray();
    }

    public function getTotalTransactionsAttribute(): int
    {
        if (!isset(self::$cached_transferIncomings[$this->id])) {
            self::$cached_transferIncomings[$this->id] = $this->transferIncomings()->pluck('status_id');
        }

        if (!isset(self::$cached_transferOutgoings[$this->id])) {
            self::$cached_transferOutgoings[$this->id] = $this->transferOutgoings()->pluck('status_id');
        }

        return self::$cached_transferIncomings[$this->id]->count() + self::$cached_transferOutgoings[$this->id]->count();
    }

    public function getTotalPendingTransactionsAttribute(): int
    {
        if (!isset(self::$cached_transferIncomings[$this->id])) {
            self::$cached_transferIncomings[$this->id] = $this->transferIncomings()->pluck('status_id');
        }

        if (!isset(self::$cached_transferOutgoings[$this->id])) {
            self::$cached_transferOutgoings[$this->id] = $this->transferOutgoings()->pluck('status_id');
        }

        return self::$cached_transferIncomings[$this->id]->filter(function ($v) {
                return $v == PaymentStatusEnum::UNSIGNED->value;
            })->count() +
            self::$cached_transferOutgoings[$this->id]->filter(function ($v) {
                return $v == PaymentStatusEnum::UNSIGNED->value;
            })->count();
    }

    public function getLastTransactionAtAttribute(): ?string
    {
        $lastIncomingTransaction = $this->transferIncomings()->orderBy('execution_at', 'desc')->first()?->execution_at;
        $lastOutgoingTransaction = $this->transferOutgoings()->orderBy('execution_at', 'desc')->first()?->execution_at;

        if (!$lastIncomingTransaction && !$lastOutgoingTransaction) {
            return null;
        }

        return Carbon::parse($lastIncomingTransaction)
            ->max($lastOutgoingTransaction)
            ->format('Y-m-d\\TH:i:s.ZZZ\\Z');
    }

    public function isParent(): bool
    {
        return $this->parent_id == null;
    }

    public function isChild(): bool
    {
        return $this->parent_id > 0;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
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
        return $this->belongsTo(ApplicantIndividual::class, 'owner_id');
    }

    /**
     * Get relation Company
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function companyModules(): HasManyThrough
    {
        return $this->hasManyThrough(
            CompanyModule::class, Company::class,
            'id',
            'company_id',
            'company_id',
            'id',
        );
    }

    public function isActiveBankingModule(): bool
    {
        return $this->companyModules()->where('module_id', ModuleEnum::BANKING->value)->where('is_active', true)->exists();
    }


    /**
     * Get relation Payment Provider
     *
     * @return BelongsTo
     */
    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    /**
     * Get relation Iban Provider
     *
     * @return BelongsTo
     */
    public function paymentProviderIban(): BelongsTo
    {
        return $this->belongsTo(PaymentProviderIban::class, 'iban_provider_id');
    }

    public function paymentBank(): BelongsTo
    {
        return $this->belongsTo(PaymentBank::class, 'payment_bank_id');
    }

    public function transferOutgoings(): HasMany
    {
        return $this->hasMany(TransferOutgoing::class, 'account_id');
    }

    public function transferIncomings(): HasMany
    {
        return $this->hasMany(TransferIncoming::class, 'account_id');
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

    public function clientable(): MorphTo
    {
        return $this->morphTo('clientable', 'client_type', 'client_id');
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function client(): MorphTo
    {
        return $this->morphTo('client', 'client_type', 'client_id');
    }

    public function getAccountAttribute()
    {
        return $this;
    }

    public function getBankCorrespondentsAttribute()
    {
        return $this->bankCorrespondentWithCurrency();
    }

    public function bankCorrespondentWithCurrency()
    {
        return BankCorrespondent::whereHas('countryRegion', function ($query) {
                $query->where('currency_id', $this->currency_id);
            })->whereHas('paymentProvider', function ($query) {
                $query->where('payment_provider_id', $this->payment_provider_id);
            })->get();
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
            ->select('accounts.*')
            ->where(function ($q) use ($filter) {
                $q->orWhere('accounts.account_number', 'ilike', $filter['account_number'] ?? '%')
                    ->orWhere('accounts.account_name', 'ilike', $filter['account_number'] ?? '%');
            })
            ->where('accounts.account_name', 'ilike', $filter['account_name'] ?? '%')
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
            })
            ->when(isset($filter['currency_id']), function ($q) use ($filter) {
                return $q->where('currency_id', $filter['currency_id']);
            });
    }

    public static function getAccountDetailsFilter($query, $filter)
    {
        $sql = self::join('companies', 'accounts.company_id', '=', 'companies.id')
            ->join('group_role', 'accounts.group_role_id', '=', 'group_role.id')
            ->join('applicant_individual', 'accounts.owner_id', '=', 'applicant_individual.id')
            ->join('payment_provider', 'accounts.payment_provider_id', '=', 'payment_provider.id')
            ->select('accounts.*')
            ->where(function ($q) use ($query) {
                $q->orWhere('accounts.id', 'like', $query['account_name'] ?? '%')
                    ->orWhere('accounts.account_name', 'like', $query['account_name'] ?? '%');
            })
            ->where(function ($q) use ($filter) {
                $q->orWhere('accounts.company_id', 'like', $filter['company'] ?? '%')
                    ->orWhere('companies.name', 'like', $filter['company'] ?? '%');
            })
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
        if (isset($filter['group_type_id'])) {
            $sql = $sql->where('accounts.group_type_id', '=', $filter['group_type_id']);
        }

        return $sql;
    }

    public function getHistoryColumns(): array
    {
        return ['account_state_id'];
    }

    public function getHistoryActions(): array
    {
        return ['saving'];
    }

    public function enableHistory(): bool
    {
        return true;
    }

    public static function getObServer(): string
    {
        return AccountObserver::class;
    }

}
