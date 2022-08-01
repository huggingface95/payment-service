<?php

namespace App\Models;

class CommissionTemplateLimitType extends BaseModel
{
    public const ALL = 'All Transaction Amount';

    public const TRANSACTION_AMOUNT = 'Single Transaction Amount';

    public const TRANSACTION_COUNT = 'Transaction Count';

    public const TRANSFER_COUNT = 'Transfer Count';

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
