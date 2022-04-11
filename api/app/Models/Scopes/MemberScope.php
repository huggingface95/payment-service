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
        $memberId = BaseModel::DEFAULT_MEMBER_ID;
        $companyId = Members::where('id', '=', $memberId)->value('company_id');
        $companyMembers = Members::where('company_id', '=', $companyId)->get('id');
        $result = collect($companyMembers)->pluck('id')->toArray();
        $builder->whereIn('member_id', $result);
    }
}
