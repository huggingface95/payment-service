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

    private function isAdministrator(): bool
    {
        return $this->attributes['type'] == self::ADMINISTRATION;
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

    public function group(bool $filter = true): BelongsTo
    {
        return $this->belongsTo(Groups::class, 'group_id')->when($filter, function ($query) {
            return $query->whereIn('name', $this->isAdministrator()
                ? [Groups::MEMBER]
                : [Groups::COMPANY, Groups::INDIVIDUAL]
            );
        });
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
