<?php

namespace App\Models;

/**
 * Class TransferBetweenUser
 */
class TransferBetweenUser extends BaseModel
{
    protected $table = 'transfer_between_users_view';

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSSSSZ',
    ];
}
