<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use App\Enums\ModuleEnum;
use App\Events\Applicant\ApplicantCompanyUpdatedEvent;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ApplicantCompany
 *
 * @property ApplicantIndividual $applicantIndividuals
 * @property ApplicantIndividual $applicantsWithBankingAccess
 * @property Company company
 */
class ApplicantCompany extends BaseModel
{
    use MorphToOne;
    use BelongsToOne;

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
    ];

    protected $casts = [
        'company_info_additional_fields'=>'array',
        'contacts_additional_fields'=>'array',
        'profile_additional_field'=>'array',
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
        'incorporate_date' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    protected static function booting()
    {
        self::created(function (ApplicantCompany $model) {
            $model->modules()->saveMany([new ApplicantCompanyModules([
                'module_id' => ModuleEnum::KYC->value
            ])]);
        });
        parent::booting();
    }

    protected $appends = ['fullname'];

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
        return $this->belongsTo(Project::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'applicant_company_modules', 'applicant_company_id', 'module_id')->withPivot('is_active');
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

    public function account(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphToOne(Account::class, 'client', AccountIndividualCompany::class, 'client_id', 'account_id');
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
