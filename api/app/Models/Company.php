<?php

namespace App\Models;

use App\Enums\ModuleEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class Company
 *
 * @property int id
 * @property string backoffice_support_url
 * @property Collection $paymentProviders
 * @property Collection $paymentProvidersIban
 */
class Company extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'email',
        'company_number',
        'contact_name',
        'country_id',
        'zip',
        'address',
        'city',
        'additional_fields_info',
        'additional_fields_basic',
        'additional_fields_settings',
        'additional_fields_data',
        'phone',
        'reg_address',
        'tax_id',
        'incorporate_date',
        'employees_id',
        'type_of_industry_id',
        'license_number',
        'exp_date',
        'state_id',
        'state_reason_id',
        'reg_number',
        'entity_type',
        'vv_token',
        'member_verify_url',
        'backoffice_login_url',
        'backoffice_forgot_password_url',
        'backoffice_support_url',
        'backoffice_support_email',
        'logo_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'deleted_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'incorporate_date' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'exp_date' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected $appends = [
        'members_count',
        'projects_count',
        'logo_link',
    ];

    public const DEFAULT_LOGO_PATH = '/img/logo.png';

    protected static function booting()
    {
        self::created(function (self $model) {
            $model->modules()->saveMany([new CompanyModule([
                'module_id' => ModuleEnum::KYC->value,
            ])]);
        });
        parent::booting();
    }

    public function getLogoLinkAttribute(): string
    {
        $defaultLogoPath = storage_path('app') . self::DEFAULT_LOGO_PATH;

        return $this->logo->link ?? $defaultLogoPath;
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo('App\Models\Languages', 'language_id');
    }

    public function ledgerSettings(): BelongsTo
    {
        return $this->belongsTo(CompanyLedgerSettings::class, 'company_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Members::class, 'company_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CompanyModule::class, 'company_id', 'id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'company_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employees_id', 'id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(DepartmentPosition::class, 'company_id');
    }

    public function paymentProviders(): HasMany
    {
        return $this->hasMany(PaymentProvider::class, 'company_id');
    }

    public function paymentProvidersIban(): HasMany
    {
        return $this->hasMany(PaymentProviderIban::class, 'company_id');
    }

    public function paymentSystem(): HasOneThrough
    {
        return $this->hasOneThrough(
            PaymentSystem::class,
            PaymentProvider::class,
            'company_id',
            'payment_provider_id',
            'id',
            'id',
        );
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'company_id');
    }

    public function regions(): HasOne
    {
        return $this->hasOne(Region::class, 'company_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function stateReason(): BelongsTo
    {
        return $this->belongsTo(StateReason::class, 'state_reason_id');
    }

    public function typeOfIndustry(): BelongsTo
    {
        return $this->belongsTo(TypeOfIndustry::class, 'type_of_industry_id');
    }

    public function scopeMemberSort($query, $sort)
    {
        return $query->withCount('members')->orderBy('members_count', $sort);
    }

    public function scopeCountrySort($query, $sort)
    {
        return $query->join('countries', 'companies.country_id', '=', 'countries.id')->orderBy('countries.id', $sort)->select('companies.*');
    }

    public function getMembersCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getProjectsCountAttribute(): int
    {
        return $this->projects()->count();
    }

    public function logo(): belongsTo
    {
        return $this->belongsTo(Files::class, 'logo_id');
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(CompanyRevenueAccount::class, 'company_id');
    }
}
