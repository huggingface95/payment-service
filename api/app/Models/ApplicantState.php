<?php

namespace App\Models;

class ApplicantState extends BaseModel
{
    protected $table = 'applicant_state';

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
