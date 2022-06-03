<?php

namespace App\Models;

class ApplicantStateReason extends BaseModel
{

    protected $table="applicant_state_reason";

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
