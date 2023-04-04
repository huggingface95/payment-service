<?php

namespace App\Models;

/**
 * Class Account
 *
 * @property int id
 * @property string account_number
 */
class CompanyRevenueAccount extends BaseModel
{
    protected $table = 'company_revenue_accounts';

    protected $fillable = [
        'number', 
        'company_id',
        'currency_id',
        'balance',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        parent::booted();
    }

}
