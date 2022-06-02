<?php

namespace App\Models;

class BusinessActivity extends BaseModel
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
