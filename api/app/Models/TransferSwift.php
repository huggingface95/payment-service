<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TransferSwift
 */
class TransferSwift extends BaseModel
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transfer_id',
        'transfer_type',
        'swift',
        'bank_name',
        'bank_address',
        'bank_country_id',
        'location',
        'ncs_number',
        'aba',
        'account_number',
    ];

    public function bankCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'bank_country_id');
    }
}
