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
        'name', 'hex_color_code', 'author_id'
    ];

    public $timestamps = false;

    public function applicants()
    {
        return $this->belongsToMany(ApplicantIndividual::class,'applicant_individual_label_relation','applicant_individual_label_id','applicant_individual_id');
    }

    public function getAuthor ($query, $author_id)
    {
        return $query->where('author_id', $author_id);
    }

}
