<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeesMode extends Model
{
    protected $table="fees_mode";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
