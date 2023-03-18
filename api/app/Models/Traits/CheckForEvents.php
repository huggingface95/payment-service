<?php

namespace App\Models\Traits;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantIndividual;
use App\Models\BaseModel;
use App\Models\Members;
use App\Models\PermissionFilter;
use App\Models\RoleAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait CheckForEvents
{
    private static array $FILTER_BY_COMPANY_TABLES = [
        'departments' => 'company_id',
        'members' => 'company_id',
        'applicant_individual' => 'company_id',
        'applicant_companies' => 'company_id',
        'accounts' => 'company_id',
        'group_role' => 'company_id',
        'companies' => 'id',
    ];

    private static array $FILTER_BY_COMPANY_SKIP_ACTIONS = [
        'members' => ['creating', 'saving'],
        'departments' => [],
        'applicant_individual' => [],
        'applicant_companies' => [],
        'accounts' => [],
        'group_role' => [],
        'companies' => [],
    ];


    // 'company_id' => 'companies:id,test:test_id',
    private static array $CHECK_SOFT_DELETED_RECORDS = [
        'creating' => [
            'company_id' => 'companies:id',
        ]
    ];

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
        //TODO may be changed to false in the future
        return true;
    }

    /**
     * @throws GraphqlException
     */
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
        if ($user) {
            /** @var Members|ApplicantIndividual $user */
            if ($user->is_super_admin) {
                return true;
            }

            $table = $model->getTable();
            if (array_key_exists($table, self::$FILTER_BY_COMPANY_TABLES)) {
                if ($key = $model->getAttribute(self::$FILTER_BY_COMPANY_TABLES[$table])) {
                    if (in_array($action, self::$FILTER_BY_COMPANY_SKIP_ACTIONS[$table])) {
                        return true;
                    }

                    return in_array($key, [BaseModel::SUPER_COMPANY_ID, $user->company_id]);
                }
            }
        }
        //TODO may be changed to false in the future
        return true;
    }

    /**
     * @throws GraphqlException
     */
    protected static function checkSoftDeletedRecord(string $action, Model $model): bool
    {
        if (array_key_exists($action, self::$CHECK_SOFT_DELETED_RECORDS)) {
            $columns = self::$CHECK_SOFT_DELETED_RECORDS[$action];
            $modelColumns = $model->getAttributes();
            foreach (array_intersect_key($columns, $modelColumns) as $column => $condition){
                foreach (explode(',', $condition) as $tableWithColumn){
                    list($table, $primary) = explode(':', $tableWithColumn);
                    if (DB::table($table)->whereNull('deleted_at')->where($primary, $modelColumns[$column])->doesntExist()){
                        throw new GraphqlException("{$column} not found in {$table} table", 'not found', 404);
                    }
                }
            }
        }
        return true;
    }

    private static function getPermissionFilter($mode, $action, $table, $conditions): Collection|array
    {
        return PermissionFilter::query()->with('binds')->where('mode', $mode)
            ->where(function ($q) use ($action) {
                return $action ? $q->where('action', $action) : $q->whereNull('action');
            })
            ->where('table', $table)
            ->where(function ($query) use ($conditions) {
                foreach ($conditions as $column => $value) {
                    $query->orWhere(function ($query) use ($column, $value) {
                        $query->where('column', $column)->where('value', $value);
                    });
                }
            })->get();
    }
}
