<?php

namespace App\Models\Scopes;

use App\Enums\ModuleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ModuleScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('id', '<>', ModuleEnum::KYC->value);
    }
}
