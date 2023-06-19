<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;
use App\Models\Scopes\MemberScope;
use App\Models\Traits\BaseObServerTrait;

class ApplicantRiskLevelHistory extends BaseModel
{

    use BaseObServerTrait;
    protected $table = 'applicant_individual_risk_level_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'risk_level_id', 'comment', 'applicant_id', 'member_id',
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

    public function applicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class, 'applicant_id', 'id');
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
