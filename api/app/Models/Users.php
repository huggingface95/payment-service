<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;




class Users extends BaseModel
{

    protected $table="users_view";

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }


    public function roles()
    {
        //TODO add functionality
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class,'group_id');
    }

    public function groupType(): BelongsTo
    {
        return $this->belongsTo(Groups::class,'group_type_d');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class,'role_d');
    }


}
