<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class EmailTemplate
 *
 * @property int id
 * @property int notification_id
 * @property string client_type
 * @property int client_id
 * @property ApplicantIndividual|ApplicantCompany|GroupRole|Members $client
 */
class EmailNotificationClient extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'email_notification_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notification_id', 'client_type', 'client_id',
    ];

    public $timestamps = false;

    public function client(): MorphTo
    {
        return $this->morphTo();
    }
}
