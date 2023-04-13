<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends BaseModel
{
    use HasFactory;

    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'transfer_id',
        'transfer_type',
        'currency_src_id',
        'currency_dst_id',
        'account_src_id',
        'account_dst_id',
        'balance_prev',
        'balance_next',
        'amount',
        'txtype',
        'revenue_account_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];
}
