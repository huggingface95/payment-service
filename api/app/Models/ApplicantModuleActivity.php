<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;

class ApplicantModuleActivity extends BaseModel
{
    use BaseObServerTrait;

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
