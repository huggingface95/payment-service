<?php

namespace App\Models;

use App\Models\Scopes\MemberScope;
use Illuminate\Database\Eloquent\Model;

class ApplicantRiskLevelHistory extends Model
{

    protected $table="applicant_individual_risk_level_history";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'risk_level_id', 'comment', 'applicant_id', 'manager_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new MemberScope);
    }

    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'applicant_id','id');
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
