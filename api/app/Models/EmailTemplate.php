<?php

namespace App\Models;

use App\Models\Scopes\PermissionFilterScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class EmailTemplate
 *
 * @property int id
 * @property string name
 * @property string subject
 * @property string content
 * @property string header
 * @property string footer
 * @property string type
 * @property string service_type
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Members $member
 * @property Company $company
 * @property EmailTemplateLayout $layout
 */
class EmailTemplate extends BaseModel
{
    public const ADMINISTRATION = 'administration';

    public const CLIENT = 'client';

    public const BANKING = 'banking';

    public const COMMON = 'common';

    public const SYSTEM = 'system';

    public const ADMIN = 'admin notify';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'service_type', 'use_layout', 'subject', 'content', 'member_id', 'company_id', 'name',
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new PermissionFilterScope());
    }

    public function getHtml(): string
    {
        return $this->useLayout()
            ? $this->layout->header . $this->attributes['content'] . $this->layout->footer
            : $this->attributes['content'];
    }

    public function useLayout(): bool
    {
        return (bool)$this->attributes['use_layout'];
    }

    public function getTypes(): array
    {
        return [
            self::ADMINISTRATION,
            self::CLIENT,
        ];
    }

    public static function getServiceTypes(): array
    {
        return [
            self::COMMON,
            self::BANKING,
            self::SYSTEM,
            self::ADMIN,
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

    public function layout(): BelongsTo
    {
        return $this->belongsTo(EmailTemplateLayout::class, 'company_id', 'company_id');
    }

    public function scopeHiddenClientAndAdminRows(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            return $query
                ->where(function (Builder $query) {
                    return $query->where('type', '<>', self::CLIENT)->where('service_type', '<>', self::ADMIN);
                })
                ->orWhere(function (Builder $query) {
                    return $query->where('type', '=', self::CLIENT)->where('service_type', '<>', self::ADMIN);
                })->orWhere(function (Builder $query) {
                    return $query->where('service_type', '=', self::ADMIN)->where('type', '<>', self::CLIENT);
                });
        });
    }
}
