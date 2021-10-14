<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessActivity extends Model
{

    public $timestamps = false;

    protected $table="business_activity";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


}
