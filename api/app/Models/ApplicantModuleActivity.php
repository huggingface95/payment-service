<?php

namespace App\Models;

class ApplicantModuleActivity extends BaseModel
{
    public $timestamps = false;

    protected $table = 'applicant_module_activity';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'individual',
        'corporate',
        'applicant_id',
    ];
}
