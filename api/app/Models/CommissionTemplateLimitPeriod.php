<?php

namespace App\Models;

class CommissionTemplateLimitPeriod extends BaseModel
{
    public const EACH_TIME = 'Each time';

    public const ONE_TIME = 'One time';

    public const DAILY = 'Daily';

    public const WEEKLY = 'Weekly';

    public const MONTHLY = 'Monthly';

    public const YEARLY = 'Yearly';

    public $timestamps = false;

    protected $table = 'commission_template_limit_period';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
