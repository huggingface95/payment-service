<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function companyMembers()
    {
        return $this->hasMany(Members::class, 'company_id');
    }

    public function scopeMemberCompany($query, int $memberId)
    {
        $companyId = Members::where('id', $memberId)->value('company_id');
        $companyMembers = DB::select("SELECT id FROM members WHERE company_id = ".$companyId);
        $result = collect($companyMembers)->pluck('id')->toArray();
        return $query->whereIn('member_id', $result);
    }

}
