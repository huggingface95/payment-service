<?php

namespace App\Models\Scopes;

use App\Models\BaseModel;
use App\Models\Traits\CheckForEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilterByCompanyScope implements Scope
{
    use CheckForEvents;

    public function apply(Builder $builder, Model $model)
    {
        if (BaseModel::$currentCompanyId && preg_match('/^(SELECT|select)/', $builder->getQuery()->toSql())) {
            $table = $builder->getModel()->getTable();
            if (array_key_exists($table, self::$FILTER_BY_COMPANY_TABLES)) {
                $column = $table.'.'.self::$FILTER_BY_COMPANY_TABLES[$table];
                $builder->where($column, BaseModel::$currentCompanyId);
            }
        }
    }
}
