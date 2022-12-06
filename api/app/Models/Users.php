<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Users extends BaseModel
{
    protected $table = 'users_view';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_id');
    }
}
