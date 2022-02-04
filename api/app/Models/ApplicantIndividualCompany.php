<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantIndividualCompany extends Model
{

    protected $table="applicant_individual_company";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'applicant_individual_id','id');
    }

    public function ApplicantIndividualCompanyRelation()
    {
        return $this->belongsTo(ApplicantIndividualCompanyRelation::class,'applicant_individual_company_relation_id','id');
    }

    public function ApplicantIndividualCompanyPosition()
    {
        return $this->belongsTo(ApplicantIndividualCompanyPosition::class,'applicant_individual_company_position_id','id');
    }

    public function ApplicantIndividualState()
    {
        return $this->belongsTo(ApplicantStatus::class,'applicant_individual_status_id','id');
    }

}
