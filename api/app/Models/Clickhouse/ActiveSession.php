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
}
