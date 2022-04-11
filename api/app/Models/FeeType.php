<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $table="fee_types";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
