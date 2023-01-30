<?php

namespace App\Models;

/**
 * Class TransferBetweenAccount
 */
class TransferBetweenAccount extends BaseModel
{
    protected $table = 'transfer_between_accounts_view';

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];
}
