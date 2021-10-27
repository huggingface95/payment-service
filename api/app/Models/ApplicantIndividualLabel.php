<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantIndividualLabel extends Model
{

    //protected $table="applicant_labels";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'hex_color_code'
    ];

    public $timestamps = false;

}
