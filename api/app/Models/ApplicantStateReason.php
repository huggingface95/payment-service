<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantStateReason extends Model
{

    protected $table="applicant_state_reason";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;


}
