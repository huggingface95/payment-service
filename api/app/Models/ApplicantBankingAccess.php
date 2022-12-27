<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\RoleFilterSuperAdminScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ApplicantBankingAccess
 *
 * @property float daily_limit
 * @property float monthly_limit
 * @property float operation_limit
 * @property float used_limit
 */
class ApplicantBankingAccess extends BaseModel
{
    use HasFactory;

    public $day_used_limit = 0;

    public $month_used_limit = 0;

    protected $table = 'applicant_banking_access';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id',
        'applicant_company_id',
        'member_id',
        'contact_administrator',
        'daily_limit',
        'monthly_limit',
        'operation_limit',
        'used_limit',
        'role_id',
        'grant_access',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope);
    }

    /**
     * Get relation applicant_individual
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_individual_id', 'id');
    }

    /**
     * Get relation applicant_company
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function ApplicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class, 'applicant_company_id', 'id');
    }

    /**
     * Get relation members
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function Members()
    {
        return $this->belongsTo(Members::class, 'member_id', 'id');
    }

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id')->withoutGlobalScope(RoleFilterSuperAdminScope::class);
    }
}
