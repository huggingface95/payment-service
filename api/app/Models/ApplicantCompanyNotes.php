<?php

namespace App\Models;

class ApplicantCompanyNotes extends BaseModel
{

    protected $table="applicant_company_notes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'note','applicant_company_id','member_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicant()
    {
        return $this->belongsTo(ApplicantCompany::class, ' applicant_company_id');
    }


}
