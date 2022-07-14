<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AccountIndividualsCompaniesScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->leftJoin('account_individuals_companies', 'account_individuals_companies.account_id', '=', 'accounts.id')
            ->select(['accounts.*', 'account_individuals_companies.client_id', 'account_individuals_companies.client_type']);
    }
}
