<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @method static firstOrCreate(array $array)
 */
class ProjectApiSetting extends MorphPivot
{
    public $timestamps = false;

    protected $table = 'project_api_settings';

    protected $fillable = ['project_id', 'wallet', 'api_key', 'password', 'is_active', 'provider_id', 'provider_type'];

    protected $hidden = [
        'password',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function provider(): MorphTo
    {
        return $this->morphTo('provider', 'provider_type', 'provider_id');
    }
}
