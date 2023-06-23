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
        'projects' => 'company_id',
        'applicant_individual_company_relation' => 'company_id',
        'email_templates' => 'company_id',
        'email_template_layouts' => 'company_id',
        'kyc_timeline' => 'company_id',
        'payment_provider' => 'company_id',
        'applicant_individual_company_position' => 'company_id',
        'applicant_documents' => 'company_id',
        'commission_template' => 'company_id',
        'commission_price_list' => 'company_id',
        'company_ledger_day_histories' => 'company_id',
        'company_ledger_month_histories' => 'company_id',
        'company_ledger_settings' => 'company_id',
        'company_ledger_week_histories' => 'company_id',
        'payment_provider_ibans' => 'company_id',
        'member_access_limitations' => 'company_id',
        'company_revenue_accounts' => 'company_id',
        'company_modules' => 'company_id',
        'department_position' => 'company_id',
        'email_notifications' => 'company_id',
        'email_smtps' => 'company_id',
        'price_list_pp_fees' => 'company_id',
        'regions' => 'company_id',
        'price_list_fees' => 'company_id',
        'quote_providers' => 'company_id',
        'transactions' => 'company_id',
        'transfer_incomings' => 'company_id',
        'transfer_outgoings' => 'company_id',
        'transfer_exchanges' => 'company_id',
        'roles' => 'company_id',
    ];

    private static array $FILTER_BY_COMPANY_SKIP_ACTIONS = [
        'members' => ['creating', 'saving'],
        'departments' => [],
        'applicant_individual' => [],
        'applicant_companies' => [],
        'accounts' => [],
        'group_role' => [],
        'companies' => [],
        'projects' => [],
    ];

    // 'company_id' => 'companies:id,test:test_id',
    private static array $CHECK_SOFT_DELETED_RECORDS = [
        'creating' => [
            'company_id' => 'companies:id',
        ],
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
            /** @var Members|ApplicantIndividual $user */
            $roleId = $user->role->id ?? throw new GraphqlException('Add role this user');

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
            foreach (array_intersect_key($columns, $modelColumns) as $column => $condition) {
                foreach (explode(',', $condition) as $tableWithColumn) {
                    list($table, $primary) = explode(':', $tableWithColumn);
                    if (DB::table($table)->whereNull('deleted_at')->where($primary, $modelColumns[$column])->doesntExist()) {
                        if ($table == 'companies') {
                            throw new GraphqlException('Company not found for this corporate or has been deleted.', 'not found', 404);
                        } else {
                            throw new GraphqlException("{$column} not found in {$table} table", 'not found', 404);
                        }
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
