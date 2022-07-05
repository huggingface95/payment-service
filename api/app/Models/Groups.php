<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Groups extends BaseModel
{
    protected $table = 'group_types';
    const MEMBER = 'Member';

    const COMPANY = 'Company';

    const INDIVIDUAL = 'Individual';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function groups(): HasMany
    {
        return $this->hasMany(GroupRole::class, 'group_type_id', 'id');
    }
}
