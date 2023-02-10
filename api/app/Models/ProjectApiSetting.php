<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @method static firstOrCreate(array $array)
 */
class ProjectApiSetting extends BaseModel
{
    public $timestamps = false;

    protected $fillable = ['project_id', 'wallet', 'api_key', 'password', 'is_active', 'payment_provider_id', 'payment_provider_type'];

    protected $hidden = [
        'password',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function paymentProvider(): MorphTo
    {
        return $this->morphTo('payment_provider');
    }
}
