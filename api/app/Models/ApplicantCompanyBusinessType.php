<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCompanyBusinessType extends Model
{

    protected $table = 'applicant_company_business_type';
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
