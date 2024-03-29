<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use App\Enums\ModuleEnum;
use App\Events\Applicant\ApplicantCompanyUpdatedEvent;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\ApplicantIndividualCompanyIdScope;
use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicantCompany
 *
 * @property ApplicantIndividual $applicantIndividuals
 * @property ApplicantIndividual $applicantsWithBankingAccess
 * @property Company company
 * @property Account $account
 */
class ApplicantCompany extends BaseModel
{
    use MorphToOne;
    use BelongsToOne;
    use BaseObServerTrait;
    use SoftDeletes;

    protected $table = 'applicant_companies';

    protected $dispatchesEvents = [
        'updated' => ApplicantCompanyUpdatedEvent::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'url',
        'phone',
        'country_id',
        'language_id',
        'state',
        'city',
        'address',
        'address2',
        'office_address',
        'zip',
        'reg_at',
        'expires_at',
        'tax',
        'reg_number',
        'license_number',
        'company_type',
        'owner_id',
        'account_manager_member_id',
        'company_info_additional_fields',
        'contacts_additional_fields',
        'profile_additional_fields',
        'applicant_company_business_type_id',
        'applicant_status_id',
        'applicant_state_id',
        'applicant_state_reason_id',
        'account_manager_member_id',
        'applicant_risk_level_id',
        'applicant_kyc_level_id',
        'owner_relation_id',
        'owner_position_id',
        'company_id',
        'photo_id',
        'project_id',
        'group_type_id',
        'incorporate_date',
        'basic_info_additional_field',
        'entity_id',
        'email_verification_status_id',
        'phone_verification_status_id',
        'contact_email',
        'contact_phone',
    ];

    protected $casts = [
        'company_info_additional_fields' => 'array',
        'contacts_additional_fields' => 'array',
        'profile_additional_fields' => 'array',
        'basic_info_additional_field' => 'array',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'incorporate_date' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'deleted_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public const ID_PREFIX = 'AC-';

    protected $appends = ['fullname'];

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

    public function getFullnameAttribute()
    {
        return $this->name;
    }

    /**
     * @return BelongsToMany
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantCompanyLabel::class, 'applicant_company_label_relation', 'applicant_company_id', 'applicant_company_label_id');
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Files::class, 'photo_id');
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ApplicantStatus::class, 'applicant_status_id');
    }

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(ApplicantState::class, 'applicant_state_id');
    }

    /**
     * @return BelongsTo
     */
    public function stateReason(): BelongsTo
    {
        return $this->belongsTo(ApplicantStateReason::class, 'applicant_state_reason_id');
    }

    /**
     * @return BelongsTo
     */
    public function riskLevel(): BelongsTo
    {
        return $this->belongsTo(ApplicantRiskLevel::class, 'applicant_risk_level_id');
    }

    /**
     * @return BelongsTo
     */
    public function kycLevel(): BelongsTo
    {
        return $this->belongsTo(ApplicantKycLevel::class, 'applicant_kyc_level_id');
    }

    /**
     * @return BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'account_manager_member_id');
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividual::class, 'owner_id');
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return BelongsTo
     */
    public function businessType(): BelongsTo
    {
        return $this->belongsTo(ApplicantCompanyBusinessType::class, 'applicant_company_business_type_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'applicant_company_modules', 'applicant_company_id', 'module_id')->withPivot('is_active as is_active');
    }

    public function notes(): HasMany
    {
        return$this->hasMany(ApplicantCompanyNotes::class, 'applicant_company_id');
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Languages::class, 'language_id');
    }

    public function ApplicantCompany(): BelongsTo
    {
        return $this->belongsTo(self::class, 'applicant_company_id', 'id');
    }

    public function applicantIndividualCompany(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividualCompany::class, 'id', 'applicant_company_id');
    }

    public function ownerRelation(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividualCompany::class, 'owner_id', 'applicant_id', 'applicant_individual_company_relation_id');
    }

    public function ownerPosition(): BelongsTo
    {
        return $this->belongsTo(ApplicantIndividualCompany::class, 'owner_id', 'applicant_id', 'applicant_individual_company_position_id');
    }

    public function applicantIndividuals(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantIndividual::class, ApplicantIndividualCompany::class, 'applicant_company_id', 'applicant_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function groupRole(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphToOne(GroupRole::class, 'user', GroupRoleUser::class, 'user_id', 'group_role_id');
    }

    //TODO change morphOne to MorphMany
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'clientable', 'client_type', 'client_id');
    }

    public function verificationEmailStatus(): BelongsTo
    {
        return $this->belongsTo(ApplicantVerificationStatus::class, 'email_verification_status_id');
    }

    public function verificationPhoneStatus(): BelongsTo
    {
        return $this->belongsTo(ApplicantVerificationStatus::class, 'phone_verification_status_id');
    }

    public function scopeGroupSort($query, $sort)
    {
        return $query
            ->join('group_role_members_individuals', 'group_role_members_individuals.user_id', 'applicant_companies.id')
            ->join('group_role', 'group_role.id', '=', 'group_role_members_individuals.group_role_id')
            ->where('group_role.group_type_id', GroupRole::COMPANY)
            ->orderBy('group_role.name', $sort)
            ->select('applicant_companies.*');
    }

    public function scopeCompanySort($query, $sort)
    {
        return $query->join('companies', 'companies.id', '=', 'applicant_companies.company_id')->orderBy('companies.name', $sort)->select('applicant_companies.*');
    }
}
