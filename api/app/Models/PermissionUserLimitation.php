<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class PermissionUserLimitation
 *
 * @property int id
 * @property int permission_id
 *
 */
class PermissionUserLimitation extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_id', 'user_type', 'user_id'
    ];

    public $timestamps = false;

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permissions::class, 'permission_id');
    }

    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__);
    }

}
