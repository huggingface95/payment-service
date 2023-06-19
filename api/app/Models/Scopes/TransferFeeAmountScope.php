<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TransferFeeAmountScope implements Scope
{
    public function apply(Builder $builder, Model $model): Builder
    {
        $table = $builder->getModel()->getTable();

        $transferType = ($table === 'transfer_incomings') ? 'Incoming' : 'Outgoing';

        return $builder->fromSub(function ($q) use ($table, $transferType) {
            return $q->from($table)
                ->selectRaw("{$table}.*, COALESCE(SUM(fees.fee), 0)::NUMERIC(15,5) as fee_amount")
                ->leftJoin('fees', function ($join) use ($table, $transferType) {
                    $join->on('fees.transfer_id', '=', "{$table}.id")
                        ->where('fees.transfer_type', '=', $transferType);
                })
                ->groupBy("{$table}.id");
        }, $table);
    }
}
