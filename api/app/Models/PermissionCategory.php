<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static firstOrCreate(string[] $array)
 * @method static whereName(string $string)
 */
class PermissionCategory extends BaseModel
{
    protected $table="permission_category";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function permissionsList(): HasMany
    {
        return $this->hasMany(PermissionsList::class,"permission_group_id")->orderBy('order');
    }


}
