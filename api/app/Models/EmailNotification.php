<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;


/**
 * Class EmailTemplate
 * @package App\Models
 * @property int id
 * @property string type
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class EmailNotification extends BaseModel
{
    const ADMINISTRATION = 'administration';
    const CLIENT = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id', 'company_id', 'group_id', 'type'
    ];


    public function getGroupAttribute(): ?array
    {
        return ($this->isAdministrator()
                ? $this->administratorGroup()
                : $this->clientGroup()
            )->first(['id', 'name'])->toArray() ?? null;
    }

    private function isAdministrator(): bool
    {
        return $this->attributes['type'] == self::ADMINISTRATION;
    }


    public function clientGroup(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_id');
    }

    public function administratorGroup(): BelongsTo
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(
            Companies::class,
            Members::class,
            'id',
            'id',
            'member_id',
            'company_id',
        );
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class);
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

}
