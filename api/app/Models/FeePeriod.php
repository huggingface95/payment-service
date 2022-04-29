<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePeriod extends Model
{
    protected $table="fee_period";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
