<?php

namespace App\Models;

use App\Models\Scopes\FilterByCompanyScope;
use App\Models\Traits\CheckForEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use CheckForEvents;

    public const DEFAULT_MEMBER_ID = 2;

    public const SUPER_COMPANY_ID = 1;

    //Access limitation applicant ids
    public static ?array $applicantIds = null;

    public static ?int $currentCompanyId = null;

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new FilterByCompanyScope());
    }

    protected static function booting()
    {
        self::creating(function ($model) {
            /** @var Members|ApplicantIndividual $user */
            $user = Auth::user();

            return self::filterByPermissionFilters($user, 'creating', $model)
                && self::filterByRoleActions($user, 'creating', $model)
                && self::filterByCompany($user, 'creating', $model)
                && self::checkSoftDeletedRecord('creating', $model);
        });
        self::saving(function ($model) {
            $user = Auth::user();

            return self::filterByPermissionFilters($user, 'saving', $model)
                && self::filterByRoleActions($user, 'saving', $model)
                && self::filterByCompany($user, 'saving', $model)
                && self::checkSoftDeletedRecord('saving', $model);
        });
        self::updating(function ($model) {
            $user = Auth::user();

            return self::filterByPermissionFilters($user, 'updating', $model)
                && self::filterByRoleActions($user, 'updating', $model)
                && self::filterByCompany($user, 'updating', $model)
                && self::checkSoftDeletedRecord('updating', $model);
        });
        self::deleting(function ($model) {
            $user = Auth::user();

            return self::filterByPermissionFilters($user, 'deleting', $model)
                && self::filterByRoleActions($user, 'deleting', $model)
                && self::filterByCompany($user, 'deleting', $model);
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
}
