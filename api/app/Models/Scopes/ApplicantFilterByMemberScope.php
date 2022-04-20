<?php

namespace App\Models\Scopes;

use App\Models\Members;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ApplicantFilterByMemberScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        //TODO Replace when guards are ready  Ex. Auth::user('members')
        /** @var Members $member */
        $member = Auth::user();
        if ($member instanceof Members && $member->IsShowOwnerApplicants()) {
            $builder->where('account_manager_member_id', $member->id);
        }
    }
}
