<?php

namespace App\Models;

use Illuminate\Support\Carbon;


/**
 * Class EmailTemplate
 * @package App\Models
 * @property int id
 * @property string subject
 * @property string content
 * @property string header
 * @property string footer
 * @property string type
 * @property string service_type
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class EmailTemplate extends BaseModel
{

    const ADMINISTRATION = 'administration';
    const CLIENT = 'client';

    const BANKING = 'banking';
    const COMMON = 'common';
    const SYSTEM = 'system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'service_type', 'use_layout', 'subject', 'content', 'header', 'footer'
    ];

    public function getHtml(): string
    {
        return $this->useLayout()
            ? $this->attributes['header'] . $this->attributes['content'] . $this->attributes['footer']
            : $this->attributes['content'];
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

    public function getServiceTypes(): array
    {
        return [
            self::COMMON,
            self::BANKING,
            self::SYSTEM,
        ];
    }


}
