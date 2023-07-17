<?php

namespace App\Models\Scopes;

use App\Enums\RespondentFeesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class TransferAmountSentScope implements Scope
{
    public function apply(Builder $builder, Model $model): Builder
    {
        $table = $builder->getModel()->getTable();

        return $builder->addSelect([
            '*',
            DB::raw(
                "CASE WHEN {$table}.respondent_fees_id = " . RespondentFeesEnum::CHARGED_TO_CUSTOMER->value . " THEN {$table}.amount - COALESCE(fee_amount, 0)::NUMERIC(15,5)
                WHEN {$table}.respondent_fees_id = " . RespondentFeesEnum::SHARED_FEES->value . " THEN {$table}.amount - COALESCE(fee_amount/2, 0)::NUMERIC(15,5)
                ELSE {$table}.amount END as amount_sent"
            ),
        ]);
    }
}
