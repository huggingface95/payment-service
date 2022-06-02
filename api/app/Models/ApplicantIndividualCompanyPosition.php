<?php

namespace App\Models;

class ApplicantIndividualCompanyPosition extends BaseModel
{

    protected $table='applicant_individual_company_position';
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
