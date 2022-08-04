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
            return self::filterByPermissionFilters('creating', $model);
        });
        self::saving(function ($model) {
            return self::filterByPermissionFilters('saving', $model);
        });
        self::updating(function ($model) {
            return self::filterByPermissionFilters('updating', $model);
        });
        self::deleting(function ($model) {
            return self::filterByPermissionFilters('deleting', $model);
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

    protected static function filterByPermissionFilters($action, Model $model): bool
    {
        /** @var Members $user */
        if ($user = Auth::user()) {
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
}
