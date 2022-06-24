<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class PermissionOperation
 * @property int id
 * @property string name
 * @property string referer
 *
 * @method static firstOrCreate(array $array)
 */
class PermissionOperation extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'referer', 'hidden',
    ];

    public $timestamps = false;

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            Permissions::class, 'permission_operations_parents', 'permission_operations_id', 'permission_id'
        );
    }

    public function binds(): BelongsToMany
    {
        return $this->belongsToMany(
            Permissions::class, 'permission_operations_binds', 'permission_operations_id', 'permission_id'
        );
    }
}
