<?php

namespace App\Models;

use App\Models\Scopes\ApplicantFilterByMemberScope;

class ApplicantCompanyBusinessType extends BaseModel
{
    protected $table = 'applicant_company_business_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope());
    }
}
