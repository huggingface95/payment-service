<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ApplicantBankingAccess
 * @package App\Models

 * @property float daily_limit
 * @property float monthly_limit
 * @property float operation_limit
 * @property float used_limit
 *
 */
class ApplicantBankingAccess extends Model
{

    use HasFactory;

    public $day_used_limit = 0;
    public $month_used_limit =0;

    protected $table="applicant_banking_access";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id','applicant_company_id','member_id','can_create_payment','can_sign_payment','contact_administrator','daily_limit','monthly_limit','operation_limit', 'used_limit'
    ];
    public $timestamps = false;

    /**
     * Get relation applicant_individual
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'applicant_individual_id','id');
    }

    /**
     * Get relation applicant_company
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function ApplicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class,'applicant_company_id','id');
    }

    /**
     * Get relation members
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function Members()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

}
