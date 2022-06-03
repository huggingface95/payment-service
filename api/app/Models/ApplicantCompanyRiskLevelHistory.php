<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\MemberScope;

class ApplicantCompanyRiskLevelHistory extends BaseModel
{

    protected $table="applicant_company_risk_level_history";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'risk_level_id', 'comment', 'applicant_company_id', 'member_id'
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MemberScope);
        static::addGlobalScope(new ApplicantFilterByMemberScope(parent::getApplicantIdsByAuthMember()));
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
