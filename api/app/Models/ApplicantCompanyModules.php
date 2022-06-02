<?php

namespace App\Models;

class ApplicantCompanyModules extends BaseModel
{

    protected $table="applicant_company_modules";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_company_id','applicant_module_id', 'is_active'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(ApplicantModules::class,'applicant_module_id', 'id');
    }

}
