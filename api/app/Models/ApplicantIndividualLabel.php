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

    public function scopeMemberCompany($query, int $memberId)
    {
        $companyId2 = Members::where('id', $memberId);
        $companyId = $query->join('members', 'applicant_individual_labels.member_id1', '=', 'members.id')->where('applicant_individual_labels.member_id', $companyId2->company_id)->first();
        return $companyId;
    }

}
