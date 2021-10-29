<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantState extends Model
{

    protected $table="applicant_state";

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
