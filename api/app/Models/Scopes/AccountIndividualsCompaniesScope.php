<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class AccountIndividualsCompaniesScope implements Scope
{
    public function apply(Builder $builder, Model $model): Builder
    {
        return $builder
            ->select(
                'accounts.*',
                DB::raw('(SELECT client_type FROM account_individuals_companies where account_individuals_companies.account_id = accounts.id) AS client_type'),
                DB::raw('(SELECT client_id FROM account_individuals_companies where account_individuals_companies.account_id = accounts.id) AS client_id')
            );
    }
}
