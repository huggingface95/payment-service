<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'iso'
    ];

    public $timestamps = false;

}
