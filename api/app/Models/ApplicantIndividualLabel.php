<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantIndividualLabel extends Model
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
        return $this->belongsToMany(ApplicantIndividual::class,'applicant_individual_label_relation','applicant_individual_label_id','applicant_individual_id');
    }

    public function members()
    {
        return $this->belongsTo(Members::class,'member_id','id');
    }

    public function scopeMemberCompany($query, int $companyId)
    {
        return $query->join('members', 'members.company_id', '=', 'members.id')->where('member_id', $companyId)->first();
    }

}
