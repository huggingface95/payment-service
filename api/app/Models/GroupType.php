<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupType extends BaseModel
{
    public const MEMBER = 'Member';

    public const COMPANY = 'Company';

    public const INDIVIDUAL = 'Individual';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function groups(): HasMany
    {
        return $this->hasMany(GroupRole::class, 'group_type_id', 'id');
    }
}
