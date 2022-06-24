<?php

namespace App\Models;

class ApplicantRiskLevel extends BaseModel
{
    protected $table = 'applicant_risk_level';

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
