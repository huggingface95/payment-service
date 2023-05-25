<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\MemberScope;
use App\Models\Traits\BaseObServerTrait;

class ApplicantCompanyRiskLevelHistory extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'applicant_company_risk_level_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'risk_level_id', 'comment', 'applicant_company_id', 'member_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new MemberScope());
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }

    public function applicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class, 'applicant_company_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(Members::class, 'member_id', 'id');
    }

    public function applicantRiskLevel()
    {
        return $this->belongsTo(ApplicantRiskLevel::class, 'risk_level_id', 'id');
    }
}
