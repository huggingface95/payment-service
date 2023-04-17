<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class TransferFeeAmountScope implements Scope
{
    public function apply(Builder $builder, Model $model): Builder
    {
        $table = $builder->getModel()->getTable();

        return $builder->fromSub(function ($q) use ($table) {
            return $q->from($table)->selectRaw("{$table}.*, COALESCE(SUM(fees.fee), 0)::NUMERIC(15,5) as fee_amount")
                ->leftJoin('fees', 'fees.transfer_id', '=', "{$table}.id")
                ->groupBy("{$table}.id");
        }, $table);
    }
}
