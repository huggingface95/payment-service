<?php

namespace App\Policies;

use App\Models\Permissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_VIEW_ANY);
    }

    public function view($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_VIEW);
    }

    public function create($user)
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_CREATE);
    }

    public function update($user)
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_UPDATE);
    }

    public function delete($user)
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_DELETE);
    }

    public function restore($user)
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_RESTORE);
    }

    public function forceDelete($user)
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_FORCE_DELETE);
    }

    public function export($user)
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_FORCE_DELETE);
    }
}
