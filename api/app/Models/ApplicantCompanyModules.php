<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCompanyModules extends Model
{

    protected $table="applicant_company_modules";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_company_id','applicant_module_id'
    ];
    public $timestamps = false;

    /**
     * Get relation applicant_company
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantCompany()
    {
        return $this->belongsTo(ApplicantCompany::class,'applicant_company_id','id');
    }

    /**
     * Get relation applicant_modules
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany(ApplicantModules::class,'applicant_company_modules','applicant_company_id', 'applicant_module_id');
    }

}
