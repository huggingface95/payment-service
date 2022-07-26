<?php

namespace App\Models;

class CommissionTemplateLimitType extends BaseModel
{
    const ALL = 'All Transaction Amount';

    const TRANSACTION_AMOUNT = 'Single Transaction Amount';

    const TRANSACTION_COUNT = 'Transaction Count';

    const TRANSFER_COUNT = 'Transfer Count';

    public $timestamps = false;

    protected $table = 'commission_template_limit_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
