<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantRiskLevel extends Model
{

    protected $table="applicant_risk_level";

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
