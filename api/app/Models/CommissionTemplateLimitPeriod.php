<?php

namespace App\Models;

class CommissionTemplateLimitPeriod extends BaseModel
{
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
