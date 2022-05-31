<?php

namespace App\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Ankurk91\Eloquent\MorphToOne;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Permission\Traits\HasRoles;


/**
 * Class ApplicantIndividual
 * @package App\Models

 * @property ApplicantBankingAccess $applicantBankingAccess
 *
 */
class ApplicantIndividual extends Model
{
    use HasRoles, MorphToOne, BelongsToOne;

    protected $table = "applicant_individual";
    protected $guard_name = 'api';

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
        'is_verification_phone',
        'company_id',
        'two_factor_auth_id'
    ];

    protected $casts = [
        'personal_additional_fields' => 'array',
        'contacts_additional_fields' => 'array'
    ];


    protected static function booted()
    {
        static::addGlobalScope(new ApplicantFilterByMemberScope);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function labels()
    {
        return $this->belongsToMany(ApplicantIndividualLabel::class, 'applicant_individual_label_relation', 'applicant_individual_id', 'applicant_individual_label_id');
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

    public function modules()
    {
        return $this->hasMany(ApplicantIndividualModules::class, 'applicant_individual_id', 'id');
    }

    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_individual_id', 'id');
    }

    public function companies()
    {
        return $this->belongsToMany(ApplicantCompany::class, 'applicant_individual_company', 'applicant_individual_id', 'applicant_company_id');
    }

    public function account(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphToOne(Accounts::class, 'client', AccountIndividualCompany::class, 'client_id', 'account_id');
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
        )->where('group_type_id', GroupRole::INDIVIDUAL);
    }

    /**
     * @return BelongsTo
     */
    public function twoFactorAuth()
    {
        return $this->belongsTo(TwoFactorAuthSettings::class, 'two_factor_auth_id');
    }

    public function scopeGroupSort($query, $sort)
    {
        return $query
            ->join('group_role_members_individuals', 'group_role_members_individuals.user_id', 'applicant_individual.id')
            ->join('group_role', 'group_role.id','=','group_role_members_individuals.group_role_id')
            ->where('group_role.group_type_id', GroupRole::INDIVIDUAL)
            ->orderBy('group_role.name',$sort)
            ->select('applicant_individual.*');
    }

    public function scopeCompanySort($query, $sort)
    {
        return $query->join('companies', 'companies.id', '=', 'applicant_individual.company_id')->orderBy('companies.name', $sort)->select('applicant_individual.*');
    }

    public function applicantBankingAccess(): \Ankurk91\Eloquent\Relations\BelongsToOne
    {
        return $this->belongsToOne(ApplicantBankingAccess::class, ApplicantIndividualCompany::class, 'applicant_individual_id', 'applicant_company_id', 'id', 'applicant_company_id');
    }
}
