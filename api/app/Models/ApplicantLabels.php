<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicantLabels extends Model
{

    const DEFAULT_COMPANY_ID = 7;
    const DEFAULT_APPLICANT_ID = 10;

    protected $table="applicant_individual_label_relation";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_individual_id','applicant_individual_label_id'
    ];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('company_id', function ($builder) {
            $companyId = self::DEFAULT_COMPANY_ID;
            $companyMembers = DB::select("SELECT id FROM members WHERE company_id = " . $companyId);
            $res = collect($companyMembers)->pluck('id')->toArray();
            $applicantMembers = DB::select("SELECT id FROM applicant_individual WHERE account_manager_member_id IN (" . implode(',', $res) . ")");
            foreach ($applicantMembers as $key => $value) {
                if ($value->id == self::DEFAULT_APPLICANT_ID) {
                    unset($applicantMembers[$key]);
                }
            }
            $result = collect($applicantMembers)->pluck('id')->toArray();
            $labelsId = DB::select("SELECT applicant_individual_label_id FROM applicant_individual_label_relation WHERE applicant_individual_id IN (" . implode(',', $result) . ")");
            $labelResult = collect($labelsId)->pluck('applicant_individual_label_id')->toArray();
            $f = fopen('data.txt', 'w+');
            $json = json_encode($labelResult);
            fwrite($f, $json);
            fclose($f);
            return $builder->whereIn('applicant_individual_label_id', $labelResult);
        });
    }

    /**
     * Get relation applicant_individual
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ApplicantIndividual()
    {
        return $this->belongsTo(ApplicantIndividual::class,'applicant_individual_id','applicant_individual_label_id', 'id');
    }

    /**
     * Get relation applicant_modules
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */

    public function ApplicantIndividualLabel()
    {
        return $this->belongsTo(ApplicantIndividualLabel::class,'applicant_individual_label_id','id');
    }

    public function labels()
    {
        return $this->belongsToMany(ApplicantIndividualLabel::class, 'applicant_individual_label_relation', 'applicant_individual_id', 'applicant_individual_label_id');
    }

}
