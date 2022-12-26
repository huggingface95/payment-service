<?php

namespace App\Models;

use App\Exceptions\GraphqlException;
use App\Models\Scopes\FilterByCompanyScope;
use App\Models\Traits\PermissionFilterData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use PermissionFilterData;

    public const DEFAULT_MEMBER_ID = 2;

    public const SUPER_COMPANY_ID = 1;
    public const FILTER_BY_COMPANY_TABLES = [
        'departments' => 'company_id',
        'members' => 'company_id',
        'applicant_individual' => 'company_id',
        'applicant_companies' => 'company_id',
        'accounts' => 'company_id',
        'group_role' => 'company_id',
        'companies' => 'id'
    ];

    public const FILTER_BY_COMPANY_SKIP_ACTIONS = [
        'members' => ['creating', 'saving'],
        'departments' => [],
        'applicant_individual' => [],
        'applicant_companies' => [],
        'accounts' => [],
        'group_role' => [],
        'companies' => [],
    ];

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
                && self::filterByCompany($user, 'creating', $model);
        });
        self::saving(function ($model) {
            $user = Auth::user();
            return self::filterByPermissionFilters($user, 'saving', $model)
                && self::filterByRoleActions($user, 'saving', $model)
                && self::filterByCompany($user, 'saving', $model);
        });
        self::updating(function ($model) {
            $user = Auth::user();
            return self::filterByPermissionFilters($user, 'updating', $model)
                && self::filterByRoleActions($user, 'updating', $model)
                && self::filterByCompany($user, 'updating', $model);
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

            if (RoleAction::query()
                ->where('action', $action)
                ->where('table', $model->getTable())
                ->where('role_id', $roleId)->first()) {
                throw new GraphqlException("{$action} action access denied in {$model->getTable()} table", 'permission denied', 403);
            }
        }
        //TODO may be changed to false in the future
        return true;
    }

    protected static function filterByCompany(?Model $user, string $action, Model $model): bool
    {
        $table = $model->getTable();
        /** @var Members|ApplicantIndividual $user */
        if (array_key_exists($table, self::FILTER_BY_COMPANY_TABLES)) {
            if ($key = $model->getAttribute(self::FILTER_BY_COMPANY_TABLES[$table])) {
                if (in_array($action, self::FILTER_BY_COMPANY_SKIP_ACTIONS[$table])){
                    return true;
                }
                return in_array($key, [self::SUPER_COMPANY_ID, $user->company_id]);
            }
        }
        return true;
    }
}
