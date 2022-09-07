<?php

namespace App\Models\Scopes;

use App\Models\Members;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class PermissionFilterSuperAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        /** @var Members $member */
        $member = Auth::user();

        if ($member && ! $member->is_super_admin) {
            $builder->where('is_super_admin', '=', false);
        }
    }
}
