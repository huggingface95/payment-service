<?php

namespace App\Models;

class ApplicantIndividualCompanyRelation extends BaseModel
{

    protected $table = 'applicant_individual_company_relation';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;


}
