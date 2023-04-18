<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyLedgerMonthHistory extends BaseModel
{
    public $timestamps = false;
    
    protected $fillable = [
        'account_id',
        'revenue_account_id',
        'company_id',
        'currency_id',
        'amount',
        'revenue_balance',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
