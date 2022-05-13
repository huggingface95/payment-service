<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static whereName(string $string)
 */
class PermissionsList extends Model
{
    const PRIVATE = 'private';
    const BUSINESS = 'business';

    protected $table="permissions_list";

    protected $fillable = [
        'name','type','permission_group_id', 'separator'
    ];

    public $timestamps = false;

    public function permissions(): HasMany
    {
        return $this->hasMany(Permissions::class,"permission_list_id")->orderBy('id','ASC');
    }

}
