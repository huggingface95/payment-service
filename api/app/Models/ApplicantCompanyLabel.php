<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCompanyLabel extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'hex_color_code', 'member_id'
    ];

    public $timestamps = false;

    public function applicants()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'applicant_company_label_relation','applicant_company_label_id','applicant_company_id');
    }

    public function members()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

}
