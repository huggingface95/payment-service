<?php

namespace App\Models;

class ApplicantKycLevel extends BaseModel
{
    protected $table = 'applicant_kyc_level';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
