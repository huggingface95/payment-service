<?php

namespace App\Models;

use App\Models\Scopes\PermissionOrderScope;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static firstOrCreate(string[] $array)
 * @method static whereName(string $string)
 */
class PermissionCategory extends BaseModel
{
    protected $table = 'permission_category';

    protected $fillable = [
        'name', 'order',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope(new PermissionOrderScope());
        parent::booted();
    }

    public function permissionsList(): HasMany
    {
        return $this->hasMany(PermissionsList::class, 'permission_group_id')->orderBy('order');
    }
}
