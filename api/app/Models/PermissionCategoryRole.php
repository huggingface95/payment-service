<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(string[] $array)
 * @method static whereName(string $string)
 */
class PermissionCategoryRole extends Model
{
    protected $table="permission_category_role";

    protected $fillable = [
        'is_all_companies'
    ];

    public $timestamps = false;

}
