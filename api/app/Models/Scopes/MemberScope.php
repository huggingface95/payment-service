<?php

namespace App\Models\Scopes;

use App\Models\BaseModel;
use App\Models\Members;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class MemberScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $companyId = Members::where('id', '=', BaseModel::$memberId)->value('company_id');
        if ($companyId != BaseModel::SUPER_COMPANY_ID) {
            $result = Members::query()->where('company_id', '=', $companyId)->get()->pluck('id')->toArray();
            $builder->whereIn('member_id', $result);
        }
    }
}
