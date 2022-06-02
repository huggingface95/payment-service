<?php

namespace App\Models;

class ApplicantCompanyBusinessType extends BaseModel
{

    protected $table = 'applicant_company_business_type';
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
