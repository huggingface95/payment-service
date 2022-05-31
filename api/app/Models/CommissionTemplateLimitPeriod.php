<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionTemplateLimitPeriod extends Model
{
    const EACH_TIME = 'Each time';
    const ONE_TIME = 'One time';
    const DAILY = 'Daily';
    const WEEKLY = 'Weekly';
    const MONTHLY = 'Monthly';
    const YEARLY = 'Yearly';

    public $timestamps = false;

    protected $table="commission_template_limit_period";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


}
