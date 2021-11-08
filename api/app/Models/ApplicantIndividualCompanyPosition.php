<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantIndividualCompanyPosition extends Model
{

    protected $table='applicant_individual_company_position';
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
