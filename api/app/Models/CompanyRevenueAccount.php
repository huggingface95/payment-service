<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;

/**
 * Class Account
 *
 * @property int id
 * @property string account_number
 */
class CompanyRevenueAccount extends BaseModel
{
    use BaseObServerTrait;

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
