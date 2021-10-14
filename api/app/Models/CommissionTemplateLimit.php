<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionTemplateLimit extends Model
{

    public $timestamps = false;

    protected $table="commission_template_limit";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'period_count',
        'amount',
        'currency_id',
        'commission_template_limit_type_id',
        'commission_template_limit_transfer_direction_id',
        'commission_template_limit_period_id',
        'commission_template_limit_action_type_id',

    ];


}
