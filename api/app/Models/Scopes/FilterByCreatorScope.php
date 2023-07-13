<?php

namespace App\Models\Scopes;

use App\Models\Traits\ApplicantIdsForAuthMemberTrait;
use App\Models\Traits\CheckForEvents;
use App\Models\TransferExchange;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilterByCreatorScope implements Scope
{
    use CheckForEvents;
    use ApplicantIdsForAuthMemberTrait;

    public function apply(Builder $builder, TransferOutgoing|TransferIncoming|TransferExchange|Model $model)
    {
        $condition = [];
        $table = $model->getTable();
        if (array_key_exists($table, self::$FILTER_BY_USER_TABLES)) {
            $userTypeColumn = self::$FILTER_BY_USER_TABLES[$table]['type'];
            $userIdColumn = self::$FILTER_BY_USER_TABLES[$table]['id'];
            $guards = self::$FILTER_BY_USER_TABLES[$table]['guards'];

            $condition = $this->getUserIdConditions($guards, $userTypeColumn, $userIdColumn);
        }

        $builder->when(count($condition), function (Builder $q) use ($condition) {
            $q->where(function (Builder $q) use ($condition) {
                foreach ($condition as $c) {
                    $q->orWhere(function (Builder $q) use ($c) {
                        foreach ($c as $column => $value) {
                            if (is_array($value)) {
                                $q->whereIn($column, $value);
                            } else {
                                $q->where($column, '=', $value);
                            }
                        }
                    });
                }
            });
        });
    }
}
