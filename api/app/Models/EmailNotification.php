<?php

namespace App\Models;

use Ankurk91\Eloquent\MorphToOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;


/**
 * Class EmailTemplate
 * @package App\Models
 * @property int id
 * @property string type
 * @property string recipient_type
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class EmailNotification extends BaseModel
{
    use MorphToOne;

    const ADMINISTRATION = 'administration';
    const CLIENT = 'client';

    const RECIPIENT_PERSON = 'person';
    const RECIPIENT_GROUP = 'group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'type', 'recipient_type',
    ];

    public static self $clone;

    private function isAdministrator(): bool
    {
        return $this->attributes['type'] == self::ADMINISTRATION;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(
            EmailTemplate::class,
            'email_notification_templates',
            'email_notification_id',
            'email_template_id'
        );
    }

    public function load($relations)
    {
        self::$clone = $this->replicate();
        return parent::load($relations);
    }

    public function clientable(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        try {
            $model = self::$clone;
        }
        catch (\Error $ex){
            $model = $this;
        }

        if ($model->type == self::CLIENT && $model->recipient_type == self::RECIPIENT_PERSON)
            return $this->applicantIndividual();
        elseif ($model->type == self::CLIENT && $model->recipient_type == self::RECIPIENT_GROUP)
            return $this->applicantCompany();
        elseif ($model->type == self::ADMINISTRATION && $model->recipient_type == self::RECIPIENT_GROUP)
            return $this->groupRole();
        else
            return $this->member();
    }

    public function applicantIndividual(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client', 'email_notification_clients', 'notification_id');
    }

    public function applicantCompany(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantCompany::class, 'client', 'email_notification_clients', 'notification_id');
    }

    public function groupRole(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(GroupRole::class, 'client', 'email_notification_clients', 'notification_id');
    }

    public function member(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(Members::class, 'client', 'email_notification_clients', 'notification_id');
    }
}
