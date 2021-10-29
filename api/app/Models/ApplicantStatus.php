<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantStatus extends Model
{

    protected $table="applicant_status";

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
