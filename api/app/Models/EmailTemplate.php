<?php

namespace App\Models;

use App\Models\Scopes\PermissionFilterScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * Class EmailTemplate
 * @package App\Models
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
 *
 * @property Members $member
 * @property Companies $company
 *
 */
class EmailTemplate extends BaseModel
{

    const ADMINISTRATION = 'administration';
    const CLIENT = 'client';

    const BANKING = 'banking';
    const COMMON = 'common';
    const SYSTEM = 'system';
    const ADMIN = 'admin notify';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'service_type', 'use_layout', 'subject', 'content', 'header', 'footer', 'member_id', 'company_id', 'name'
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new PermissionFilterScope);
    }

    public function getHtml(): string
    {
        return $this->attributes['header'] . $this->attributes['content'] . $this->attributes['footer'];
    }

    public function useLayout(): bool
    {
        return (boolean)$this->attributes['use_layout'];
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
            self::ADMIN
        ];
    }


    public function member(): BelongsTo
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

}
