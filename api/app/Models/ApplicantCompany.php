<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


/**
 * Class ApplicantCompany
 * @package App\Models

 * @property ApplicantIndividual $applicantIndividuals
 * @property ApplicantIndividual $applicantsWithBankingAccess
 *
 */
class ApplicantCompany extends BaseModel
{
        use MorphToOne,BelongsToOne;

    protected $table="applicant_companies";

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
        'is_verification_phone',
        'owner_relation_id',
        'owner_position_id',
        'company_id'
    ];

    protected $casts = [
        'company_info_additional_fields'=>'array',
        'contacts_additional_fields'=>'array',
        'profile_additional_field'=>'array'
    ];

    /**
     * @return BelongsToMany
     */
    public function labels()
    {
        return $this->belongsToMany(ApplicantCompanyLabel::class,'applicant_company_label_relation','applicant_company_id','applicant_company_label_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(ApplicantStatus::class,'applicant_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(ApplicantState::class,'applicant_state_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stateReason()
    {
        return $this->belongsTo(ApplicantStateReason::class,'applicant_state_reason_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function riskLevel()
    {
        return $this->belongsTo(ApplicantRiskLevel::class,'applicant_risk_level_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kycLevel()
    {
        return $this->belongsTo(ApplicantKycLevel::class,'applicant_kyc_level_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(Members::class, 'account_manager_member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function businessType()
    {
        return $this->belongsTo(ApplicantCompanyBusinessType::class,'applicant_company_business_type_id');
    }

    public function modules()
    {
        return $this->hasMany(ApplicantCompanyModules::class,'applicant_company_id','id');
    }

    public function notes()
    {
        return$this->hasMany(ApplicantCompanyNotes::class,'applicant_company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Languages::class,'language_id');
    }

    public function  ApplicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class,'applicant_company_id','id');
    }

    public function applicantIndividualCompany()
    {
        return $this->belongsTo(ApplicantIndividualCompany::class,'id', 'applicant_company_id');
    }

    public function ownerRelation()
    {
        return $this->belongsTo(ApplicantIndividualCompany::class,'owner_id', 'applicant_individual_id', 'applicant_individual_company_relation_id');
    }

    public function ownerPosition()
    {
        return $this->belongsTo(ApplicantIndividualCompany::class,'owner_id', 'applicant_individual_id', 'applicant_individual_company_position_id');
    }

    public function applicantIndividuals(): BelongsToMany
    {
        return $this->belongsToMany(ApplicantIndividual::class, ApplicantIndividualCompany::class, 'applicant_company_id', 'applicant_individual_id');
    }

    public function company()
    {
        return $this->belongsTo(Companies::class);
    }

    public function groupRole(): HasOneThrough
    {
        return $this->hasOneThrough(
            GroupRole::class,
            GroupRoleUser::class,
            'user_id',
            'id',
            'id',
            'group_role_id',
        )->where('group_type_id', GroupRole::COMPANY);
    }

    public function account(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphToOne(Accounts::class, 'client', AccountIndividualCompany::class, 'client_id', 'account_id');
    }

    public function scopeGroupSort($query, $sort)
    {
        return $query
            ->join('group_role_members_individuals', 'group_role_members_individuals.user_id', 'applicant_companies.id')
            ->join('group_role', 'group_role.id','=','group_role_members_individuals.group_role_id')
            ->where('group_role.group_type_id', GroupRole::COMPANY)
            ->orderBy('group_role.name',$sort)
            ->select('applicant_companies.*');
    }

    public function scopeCompanySort($query, $sort)
    {
        return $query->join('companies','companies.id','=','applicant_companies.company_id')->orderBy('companies.name',$sort)->select('applicant_companies.*');
    }

}
