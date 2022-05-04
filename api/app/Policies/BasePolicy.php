<?php

namespace App\Policies;

use App\Models\Permissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function view($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_VIEW);
    }

    public function create($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_CREATE);
    }

    public function update($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_UPDATE);
    }

    public function delete($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_DELETE);
    }

    public function restore($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_RESTORE);
    }

    public function forceDelete($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_FORCE_DELETE);
    }

    public function export($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_FORCE_DELETE);
    }

    public function available($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_AVAILABLE);
    }

    public function attach($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_ATTACH);
    }

    public function detach($user): bool
    {
        return $user->hasPermission(Permissions::ACTION_TYPE_DETACH);
    }

}
