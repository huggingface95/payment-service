<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionTemplateLimitActionType extends Model
{

    public $timestamps = false;

    protected $table="commission_template_limit_action_typee";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


}
