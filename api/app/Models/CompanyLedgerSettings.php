<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyLedgerSettings extends BaseModel
{
    use BaseObServerTrait;

    public $timestamps = false;

    protected $table = 'company_ledger_settings';

    protected $fillable = [
        'company_id',
        'end_of_day_time',
        'end_of_week_day',
        'end_of_week_time',
        'end_of_month_day',
        'end_of_month_time',
    ];

    protected $casts = [
        'end_of_day_time' => 'datetime:HH:mm:ss',
        'end_of_week_time' => 'datetime:HH:mm:ss',
        'end_of_month_time' => 'datetime:HH:mm:ss',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
