<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCompany extends Model
{


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
        'member_group_role_id'
    ];

    protected $casts = [
        'company_info_additional_fields'=>'array',
        'contacts_additional_fields'=>'array',
        'profile_additional_field'=>'array'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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

    public function company()
    {
        return $this->hasOneThrough(Companies::class,Members::class,'id', 'id','account_manager_member_id','company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(GroupRole::class,'member_group_role_id');
    }

}
