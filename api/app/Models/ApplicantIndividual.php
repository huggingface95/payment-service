<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ApplicantIndividual extends Model
{

    protected $table="applicant_individual";

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
        'password_salt'
    ];

    protected $casts = [
        'personal_additional_fields'=>'array',
        'contacts_additional_fields'=>'array'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function labels()
    {
        return $this->belongsToMany(ApplicantIndividualLabel::class,'applicant_individual_label_relation','applicant_individual_id','applicant_individual_label_id');
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
    public function manager()
    {
        return $this->belongsTo(Members::class, 'account_manager_member_id');
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
    public function language()
    {
        return $this->belongsTo(Languages::class,'language_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function citizenshipCountry()
    {
        return $this->belongsTo(Country::class,'citizenship_country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function birthCountry()
    {
        return $this->belongsTo(Country::class,'birth_country_id');
    }

    public function notes()
    {
        return$this->hasMany(ApplicantIndividualNotes::class,'applicant_individual_id');
    }

    public function getCompanyAttribute()
    {
        return $this->manager()
            ->join('companies', 'company.id', '=', 'members.company_id')->select('companies.*')->first();
    }

    public function modules()
    {
        return $this->belongsToMany(ApplicantModules::class,'applicant_individual_modules','applicant_individual_id','applicant_module_id');
    }


}
