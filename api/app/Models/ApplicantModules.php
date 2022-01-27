<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;

class ApplicantModules extends BaseModel
{
    public $timestamps = false;

    protected $table="applicant_modules";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function modules()
    {
        return $this->belongsToMany(ApplicantModules::class,'applicant_individual_modules','applicant_individual_id','applicant_module_id');
    }


}
