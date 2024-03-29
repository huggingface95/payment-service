<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class EmailTemplate
 *
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
 * @property bool is_sending_mail
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class EmailSmtp extends BaseModel
{
    use BaseObServerTrait;
    public const SECURITY_AUTO = 'auto';

    public const SECURITY_SSL = 'ssl';

    public const SECURITY_STARTTLS = 'starttls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'member_id', 'security', 'company_id', 'host_name', 'from_name', 'from_email', 'username', 'password', 'replay_to', 'port', 'is_sending_mail',
    ];

    protected $casts = [
        'created_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
        'updated_at' => 'datetime:YYYY-MM-DDTHH:mm:ss.SSSZ',
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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
