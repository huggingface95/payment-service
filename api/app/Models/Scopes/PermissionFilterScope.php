<?php

namespace App\Models\Scopes;

use App\Models\Members;
use App\Models\PermissionFilter;
use App\Models\Traits\PermissionFilterData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PermissionFilterScope implements Scope
{
    use PermissionFilterData;

    public function apply(Builder $builder, Model $model)
    {
        $conditions = self::getEloquentSqlWithBindings($builder);

        /** @var Members $user */
        $user = \auth()->user();
        $allPermissions = $user->getAllPermissions();

        $filter = self::getPermissionFilter(PermissionFilter::SCOPE_MODE, null, $model->getTable(), $conditions);

        if ($filter) {
            $bindPermissions = $filter->binds->intersect($allPermissions);

            if ($bindPermissions->count() != $filter->binds->count()) {
                $builder->where($filter->column, '<>', $filter->value);
            }
        }
    }

    private static function getEloquentSqlWithBindings(Builder $builder): array
    {
        $wheres = $builder->withoutGlobalScope(PermissionFilterScope::class)->getQuery()->wheres;

        return count($wheres) ? collect($wheres[0]['query']->wheres)->pluck('value', 'column')->toArray() : [];
    }
}
