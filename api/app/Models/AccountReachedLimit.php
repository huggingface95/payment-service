<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AccountReachedLimit
 *
 * @property int id
 * @property int account_id
 * @property string group_type
 * @property string client_name
 * @property string client_type
 * @property string transfer_direction
 * @property string limit_type
 * @property int limit_value
 * @property string limit_currency
 * @property int period
 * @property float amount
 * @property Account $account
 */
class AccountReachedLimit extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'group_type', 'client_name', 'client_type', 'transfer_direction', 'limit_type', 'limit_value', 'limit_currency', 'period', 'amount',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'expires_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    protected $dates = ['expires_at'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
