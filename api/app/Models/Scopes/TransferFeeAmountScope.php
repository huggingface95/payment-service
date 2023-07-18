<?php

namespace App\Models\Scopes;

use App\Enums\RespondentFeesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TransferFeeAmountScope implements Scope
{
    protected $calculateAmountSent;

    public function __construct($calculateAmountSent = false)
    {
        $this->calculateAmountSent = $calculateAmountSent;
    }

    public function apply(Builder $builder, Model $model): Builder
    {
        $table = $builder->getModel()->getTable();

        $transferType = ($table === 'transfer_incomings') ? 'Incoming' : 'Outgoing';

        return $builder->fromSub(function ($q) use ($table, $transferType) {
            $q->from($table)
                ->selectRaw("{$table}.*, COALESCE(SUM(fees.fee), 0)::NUMERIC(15,5) as fee_amount");

            if ($this->calculateAmountSent) {
                $q->selectRaw(
                    "CASE 
                        WHEN {$table}.respondent_fees_id = " . RespondentFeesEnum::CHARGED_TO_BENEFICIARY->value . " THEN {$table}.amount - COALESCE(SUM(fees.fee), 0)::NUMERIC(15,5)
                        WHEN {$table}.respondent_fees_id = " . RespondentFeesEnum::SHARED_FEES->value . " THEN {$table}.amount - COALESCE(SUM(fees.fee)/2, 0)::NUMERIC(15,5)
                        ELSE {$table}.amount 
                    END as amount_sent"
                );
            }

            $q->leftJoin('fees', function ($join) use ($table, $transferType) {
                $join->on('fees.transfer_id', '=', "{$table}.id")
                    ->where('fees.transfer_type', '=', $transferType);
            });

            $q->groupBy("{$table}.id");
        }, $table);
    }
}
