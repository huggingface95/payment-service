<?php

namespace App\Models;

class ApplicantStatus extends BaseModel
{
    protected $table = 'applicant_status';

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
