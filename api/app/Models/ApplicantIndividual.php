<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use App\Enums\ModuleEnum;
use App\Events\Applicant\ApplicantIndividualUpdatedEvent;
use App\Models\Clickhouse\ActiveSession;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\ApplicantIndividualCompanyIdScope;
use App\Models\Traits\BaseObServerTrait;
use App\Models\Traits\UserPermission;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class ApplicantIndividual
 *
 * @property int id
 * @property string email
 * @property string fullname
 * @property int company_id
 * @property int account_manager_member_id
 * @property ApplicantBankingAccess $applicantBankingAccess
 * @property Company company
 * @property Account $account
 * @property ?array $active_session
 */
class ApplicantIndividual extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject, CanResetPasswordContract
{
    use Authorizable;
    use Authenticatable;
    use HasApiTokens;
    use CanResetPassword;
    use MorphToOne;
    use BelongsToOne;
    use UserPermission;
    use HasRelationships;
    use BaseObServerTrait;
    use SoftDeletes;

    protected $table = 'applicant_individual';

    protected $guard = 'api_client';

    protected $dispatchesEvents = [
        'updated' => ApplicantIndividualUpdatedEvent::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'url',
        'phone',
        'country_id',
        'language_id',
        'state',
        'city',
        'address',
        'zip',
        'nationality',
        'birth_country_id',
        'birth_state',
        'birth_city',
        'birth_at',
        'sex',
        'citizenship_country_id',
        'personal_additional_fields',
        'contacts_additional_fields',
        'profile_additional_fields',
        'applicant_status_id',
        'applicant_state_id',
        'applicant_state_reason_id',
        'applicant_risk_level_id',
        'account_manager_member_id',
        'password_hash',
        'password_salt',
        'email_verification_status_id',
        'phone_verification_status_id',
        'company_id',
        'two_factor_auth_setting_id',
        'photo_id',
        'notify_device_email',
        'project_id',
        'group_type_id',
        'kyc_level_id',
        'entity_id',
        'address_additional_fields',
        'last_screened_at',
        'is_need_change_password',
    ];

    protected $hidden = [
        'password_hash',
        'password_salt',
        'google2fa_secret',
        'security_pin',
    ];

    protected $casts = [
        'personal_additional_fields' => 'array',
        'contacts_additional_fields' => 'array',
        'profile_additional_fields' => 'array',
        'address_additional_fields' => 'array',
        'backup_codes' => 'array',
        'birth_at' => 'date:Y-m-d',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'last_screened_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'deleted_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected $appends = [
        'two_factor',
        'is_need_change_password',
        'active_session',
        'company_name',
    ];

    public const ID_PREFIX = 'AI-';

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
        static::addGlobalScope(new ApplicantIndividualCompanyIdScope());
    }

    protected static function booting()
    {
        self::created(function (self $model) {
            $model->modules()->attach([ModuleEnum::KYC->value]);
        });
        parent::booting();
    }

    public function getPrefixName(): string
    {
        return self::ID_PREFIX;
    }

    public function getPrefixAttribute(): string
    {
        return self::ID_PREFIX. $this->attributes['id'];
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'client_type' => 'applicant',
        ];
    }

    public function getCompanyNameAttribute()
    {
        return $this->company()->first()?->name;
    }

    public function getTwoFactorAttribute(): bool
    {
        return ($this->google2fa_secret) ? true : false;
    }

    public function getIsNeedChangePasswordAttribute(): bool
    {
        return false;
    }

    public function getActiveSessionAttribute(): ?array
    {
        $activeSession = DB::connection('clickhouse')
            ->query()
            ->from((new ActiveSession())->getTable())
            ->where('provider', '=', 'applicant')
            ->where('email', '=', $this->email)
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->first();

        if (! is_null($activeSession) && Carbon::parse($activeSession['expired_at'] ?? 0)->timestamp >= Carbon::parse()->timestamp) {
            $activeSession['current_session'] = true;
        }

        return $activeSession;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function labels()
    {
        return $this->belongsToMany(ApplicantIndividualLabel::class, 'applicant_individual_label_relation', 'applicant_individual_id', 'applicant_individual_label_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(ApplicantStatus::class, 'applicant_status_id');
    }

    /**
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(ApplicantState::class, 'applicant_state_id');
    }

    /**
     * @return BelongsTo
     */
    public function stateReason()
    {
        return $this->belongsTo(ApplicantStateReason::class, 'applicant_state_reason_id');
    }

    /**
     * @return BelongsTo
     */
    public function riskLevel()
    {
        return $this->belongsTo(ApplicantRiskLevel::class, 'applicant_risk_level_id');
    }

    /**
     * @return BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(Members::class, 'account_manager_member_id');
    }

    /**
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Languages::class, 'language_id');
    }

    /**
     * @return BelongsTo
     */
    public function citizenshipCountry()
    {
        return $this->belongsTo(Country::class, 'citizenship_country_id');
    }

    public function bankingAccess(): HasMany
    {
        return $this->hasMany(ApplicantBankingAccess::class, 'applicant_individual_id');
    }

    /**
     * @return BelongsTo
     */
    public function birthCountry()
    {
        return $this->belongsTo(Country::class, 'birth_country_id');
    }

    public function notes()
    {
        return $this->hasMany(ApplicantIndividualNotes::class, 'applicant_individual_id');
    }

    public function getCreatedForAttribute()
    {
        return $this->manager()
            ->join('companies', 'companies.id', '=', 'members.company_id')->select('companies.*')->first();
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'applicant_individual_modules', 'applicant_individual_id', 'module_id')->withPivot('is_active as is_active');
    }

    public function moduleActivity(): HasMany
    {
        return $this->hasMany(ApplicantModuleActivity::class, 'applicant_id');
    }

    public function ApplicantIndividual()
    {
        return $this->belongsTo(self::class, 'applicant_individual_id', 'id');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantCompany::class, 'applicant_individual_company', 'applicant_id', 'applicant_company_id');
    }

    //TODO change morphOne to MorphMany
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'clientable', 'client_type', 'client_id');
    }

    public function groupRole(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphToOne(GroupRole::class, 'user', GroupRoleUser::class, 'user_id', 'group_role_id');
    }

    public function role(): HasOneDeep
    {
        return $this->hasOneDeep(
            Role::class,
            [GroupRoleUser::class, GroupRole::class],
            [
                'user_id',
                'id',
                'id',
            ],
            [
                'id',
                'group_role_id',
                'role_id',
            ]
        )->where('user_type', class_basename(self::class));
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function groupRoles(): MorphToMany
    {
        return $this->morphToMany(GroupRole::class, 'user', GroupRoleUser::class, 'user_id', 'group_role_id');
    }

    public function twoFactorAuth(): BelongsTo
    {
        return $this->belongsTo(TwoFactorAuthSettings::class, 'two_factor_auth_setting_id');
    }

    public function ipAddress(): HasMany
    {
        return $this->hasMany(ClientIpAddress::class, 'client_id')->where('client_type', '=', class_basename(self::class));
    }

    public function applicantBankingAccess(): \Ankurk91\Eloquent\Relations\BelongsToOne
    {
        return $this->belongsToOne(ApplicantBankingAccess::class, ApplicantIndividualCompany::class, 'applicant_individual_id', 'applicant_company_id', 'id', 'applicant_company_id');
    }

    public function applicantIndividualCompanies(): HasMany
    {
        return $this->hasMany(ApplicantIndividualCompany::class, 'applicant_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(Files::class, 'author_id')->where('entity_type', 'applicant_individual');
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'photo_id');
    }

    public function verificationEmailStatus(): BelongsTo
    {
        return $this->belongsTo(ApplicantVerificationStatus::class, 'email_verification_status_id');
    }

    public function verificationPhoneStatus(): BelongsTo
    {
        return $this->belongsTo(ApplicantVerificationStatus::class, 'phone_verification_status_id');
    }

    public function kycLevel(): BelongsTo
    {
        return $this->belongsTo(ApplicantKycLevel::class, 'kyc_level_id');
    }

    public function scopeGroupSort($query, $sort)
    {
        return $query
            ->join('group_role_members_individuals', 'group_role_members_individuals.user_id', 'applicant_individual.id')
            ->join('group_role', 'group_role.id', '=', 'group_role_members_individuals.group_role_id')
            ->where('group_role.group_type_id', GroupRole::INDIVIDUAL)
            ->orderBy('group_role.name', $sort)
            ->select('applicant_individual.*');
    }

    public function scopeCompanySort($query, $sort)
    {
        return $query->join('companies', 'companies.id', '=', 'applicant_individual.company_id')->orderBy('companies.name', $sort)->select('applicant_individual.*');
    }

    public function scopeGetGroup(Builder $query, $groupId)
    {
        return $query->join('group_role_members_individuals', 'applicant_individual.id', '=', 'group_role_members_individuals.user_id')
            ->join('group_role', 'group_role_members_individuals.group_role_id', '=', 'group_role.id')
            ->where('group_role_members_individuals.user_type', '=', self::class)
            ->where('group_role.id', '=', $groupId)
            ->select('applicant_individual.*');
    }
}
