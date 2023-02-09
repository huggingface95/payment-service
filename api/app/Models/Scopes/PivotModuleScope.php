<?php

namespace App\Models\Scopes;

use App\Enums\ModuleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PivotModuleScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('module_id', '<>', ModuleEnum::KYC->value);
    }
}
