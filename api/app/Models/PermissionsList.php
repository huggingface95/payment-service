<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static whereName(string $string)
 * @method static firstOrCreate(array $uniqueData)
 * @method static where(string $string, string $string1)
 */
class PermissionsList extends BaseModel
{
    public const PRIVATE = 'private';

    public const BUSINESS = 'business';
    public const SIGN_PAYMENTS = 'Sign Payments';

    protected $table = 'permissions_list';

    protected $fillable = [
        'name', 'type', 'permission_group_id', 'order',
    ];

    public $timestamps = false;

    public function permissions(): HasMany
    {
        return $this->hasMany(Permissions::class, 'permission_list_id');
    }
}
