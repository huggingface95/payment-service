<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * Class EmailTemplate
 * @package App\Models
 * @property int id
 * @property int member_id
 * @property string name
 * @property string security
 * @property string host_name
 * @property int port
 * @property string from_name
 * @property string from_email
 * @property string username
 * @property string password
 * @property string replay_to
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class EmailSmtp extends BaseModel
{

    const SECURITY_AUTO = 'auto';
    const SECURITY_SSL = 'ssl';
    const SECURITY_STARTTLS = 'starttls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'member_id', 'security', 'host_name', 'from_name', 'from_email', 'username', 'password', 'replay_to', 'port'
    ];

    public static function getSecurities(): array
    {
        return [
            self::SECURITY_AUTO,
            self::SECURITY_SSL,
            self::SECURITY_STARTTLS,
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

}
