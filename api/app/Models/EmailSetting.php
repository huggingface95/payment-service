<?php

namespace App\Models;



/**
 * Class EmailSetting
 * @package App\Models
 * @property int id
 * @property string name
 *
 */
class EmailSetting extends BaseModel
{


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
