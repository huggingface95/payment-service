<?php

namespace App\Models\Clickhouse;

use Carbon\Carbon;
use PhpClickHouseLaravel\BaseModel;

/**
 * Class ActiveSession
 *
 * @property Carbon expired_at

 */
class ActiveSession extends BaseModel
{
    protected $table = 'active_sessions';

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'expired_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
    ];
}
