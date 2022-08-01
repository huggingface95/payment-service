<?php

namespace App\Models;

use Ankurk91\Eloquent\MorphToOne;
use App\Models\Scopes\ApplicantFilterByMemberScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Class EmailTemplate
 *
 * @property int id
 * @property string type
 * @property string recipient_type
 * @property string group_type
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int $group_type_id
 */
class EmailNotification extends BaseModel
{
    use MorphToOne;

    public const ADMINISTRATION = 'administration';

    public const CLIENT = 'client';

    public const RECIPIENT_PERSON = 'person';

    public const RECIPIENT_GROUP = 'group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'type', 'recipient_type', 'group_type_id', 'group_role_id',
    ];

    public static self $clone;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ApplicantFilterByMemberScope(parent::getApplicantIdsByAuthMember()));
    }

    private function isAdministrator(): bool
    {
        return $this->attributes['type'] == self::ADMINISTRATION;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_role_id');
    }

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
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

    public function clientable(): ?\Ankurk91\Eloquent\Relations\MorphToOne
    {
        try {
            $model = self::$clone;
        } catch (\Error $ex) {
            $model = $this;
        }

        $name = $model->groupType->name ?? null;

        if ($name == GroupType::MEMBER) {
            return $this->member();
        } elseif ($name == GroupType::COMPANY) {
            return $this->applicantCompany();
        } elseif ($name == GroupType::INDIVIDUAL) {
            return $this->applicantIndividual();
        }

        return null;
    }

    public function applicantIndividual(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantIndividual::class, 'client', 'email_notification_clients', 'notification_id');
    }

    public function applicantCompany(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(ApplicantCompany::class, 'client', 'email_notification_clients', 'notification_id');
    }

    public function member(): \Ankurk91\Eloquent\Relations\MorphToOne
    {
        return $this->morphedByOne(Members::class, 'client', 'email_notification_clients', 'notification_id');
    }
}
