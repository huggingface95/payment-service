<?php

namespace App\Models;

class CommissionTemplateLimitActionType extends BaseModel
{

    public $timestamps = false;

    protected $table="commission_template_limit_action_type";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


}
