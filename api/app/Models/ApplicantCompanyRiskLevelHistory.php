<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCompanyRiskLevelHistory extends Model
{

    const DEFAULT_MEMBER_ID = 2;

    protected $table="applicant_company_risk_level_history";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'risk_level_id', 'comment', 'applicant_company_id', 'manager_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_id', function ($builder) {
            $memberId = self::DEFAULT_MEMBER_ID;
            $companyId = Members::where('id', '=', $memberId)->value('company_id');
            $companyMembers = Members::where('company_id', '=', $companyId)->get('id');
            $result = collect($companyMembers)->pluck('id')->toArray();
            return $builder->whereIn('manager_id', $result);
        });
    }

    public function ApplicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class,'applicant_company_id','id');
    }

    public function Members()
    {
        return $this->belongsTo(Members::class,'manager_id','id');
    }

    public function ApplicantRiskLevel()
    {
        return $this->belongsTo(ApplicantRiskLevel::class,'risk_level_id','id');
    }

}
