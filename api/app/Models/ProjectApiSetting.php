<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectApiSetting extends BaseModel
{
    public $timestamps = false;

    protected $fillable = ['project_id', 'wallet', 'api_key', 'password'];

    protected $hidden = [
        'password',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
