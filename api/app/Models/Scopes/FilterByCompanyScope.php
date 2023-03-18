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
            $filteredTables = array_map(function ($table) {
                return sprintf('%s%s', $table, '(?=[\.\"\' ])');
            }, array_keys(self::$FILTER_BY_COMPANY_TABLES));

            if (preg_match(sprintf('/%s/', implode('|', $filteredTables)), $builder->getQuery()->toSql(), $matches)) {
                foreach ($matches as $match) {
                    $column = $match . '.' . self::$FILTER_BY_COMPANY_TABLES[$match];
                    $builder->where($column, BaseModel::$currentCompanyId);
                }
            }
        }
    }
}
