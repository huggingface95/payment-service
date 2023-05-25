<?php

namespace App\Models;

use App\Models\Traits\BaseObServerTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GroupRoleUser extends BaseModel
{
    use BaseObServerTrait;

    protected $table = 'group_role_members_individuals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_role_id', 'user_id', 'user_type',
    ];

    public $timestamps = false;

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
