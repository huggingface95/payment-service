<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantKycLevel extends Model
{

    protected $table="applicant_kyc_level";

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
