<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * Class PermissionOperation
 * @package App\Models
 * @property int id
 * @property string name
 * @property string referer
 *
 */
class PermissionOperation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'referer'
    ];


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
