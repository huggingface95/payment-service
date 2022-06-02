<?php

namespace App\Models;

class ApplicantIndividualCompany extends BaseModel
{

    protected $table="applicant_individual_company";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id', 'applicant_company_id', 'applicant_individual_company_relation_id', 'applicant_individual_company_position_id'
    ];

    public $timestamps = false;

    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'applicant_individual_id', 'id');
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
