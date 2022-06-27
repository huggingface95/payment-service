<?php

namespace App\Models;

class ApplicantModules extends BaseModel
{
    public $timestamps = false;

    protected $table = 'applicant_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
