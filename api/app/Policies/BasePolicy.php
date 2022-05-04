<?php

namespace App\Policies;

use App\Models\Permissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function view($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_VIEW);
    }

    public function create($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_CREATE);
    }

    public function update($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_UPDATE);
    }

    public function delete($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_DELETE);
    }

    public function restore($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_RESTORE);
    }

    public function forceDelete($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_FORCE_DELETE);
    }

    public function export($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_FORCE_DELETE);
    }

    public function available($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_AVAILABLE);
    }

    public function attach($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_ATTACH);
    }

    public function detach($user, $model): bool
    {
        return true;
        return $user->hasPermission(get_class($model), Permissions::ACTION_TYPE_DETACH);
    }

}
