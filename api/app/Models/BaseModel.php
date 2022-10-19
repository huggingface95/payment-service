<?php

namespace App\Models;

use App\Models\Traits\PermissionFilterData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use PermissionFilterData;

    public const DEFAULT_MEMBER_ID = 2;

    //Access limitation applicant ids
    public static ?array $applicantIds = null;

    protected static function booting()
    {
        self::creating(function ($model) {
            $user = Auth::user();
            return self::filterByPermissionFilters($user, 'creating', $model)
                && self::filterByRoleActions($user, 'creating', $model);
        });
        self::saving(function ($model) {
            $user = Auth::user();
            return self::filterByPermissionFilters($user, 'saving', $model)
                && self::filterByRoleActions($user, 'saving', $model);
        });
        self::updating(function ($model) {
            $user = Auth::user();
            return self::filterByPermissionFilters($user, 'updating', $model)
                && self::filterByRoleActions($user, 'updating', $model);
        });
        self::deleting(function ($model) {
            $user = Auth::user();
            return self::filterByPermissionFilters($user, 'deleting', $model)
                && self::filterByRoleActions($user, 'deleting', $model);
        });

        parent::booting();
    }

    protected function setArrayAttribute($value)
    {
        return str_replace(['[', ']'], ['{', '}'], json_encode($value));
    }

    protected function getArrayAttribute($value)
    {
        return json_decode(str_replace(['{', '}'], ['[', ']'], $value));
    }

    protected static function filterByPermissionFilters(?Model $user, string $action, Model $model): bool
    {
        if ($user) {
            /** @var Members $user */
            $allPermissions = $user->getAllPermissions();

            $filters = self::getPermissionFilter(PermissionFilter::EVENT_MODE, $action, $model->getTable(), $model->getAttributes());

            foreach ($filters as $filter) {
                $bindPermissions = $filter->binds->intersect($allPermissions);
                if ($bindPermissions->count() != $filter->binds->count()) {
                    return false;
                }
            }
        }

        return true;
    }

    protected static function filterByRoleActions(?Model $user, string $action, Model $model): bool
    {
        if ($user) {
            /** @var Members $user */
            $roleId = $user->role->id;

            /** @var RoleAction $roleAction */
            return !RoleAction::query()
                ->where('action', $action)
                ->where('table', $model->getTable())
                ->where('role_id', $roleId)->first();
        }
        //TODO may be changed to false in the future
        return true;
    }
}
