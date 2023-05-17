<?php

namespace App\GraphQL\Queries;

use App\GraphQL\Handlers\FilterConditionsHandler;
use App\Models\TransferExchange;
use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TransferExchangeQuery
{
    public function __construct(
        protected FilterConditionsHandler $handler
    ) {
    }

    public function get($root, array $args): TransferExchange
    {
        $transfers = TransferExchange::findOrFail($args['id']);

        return $transfers;
    }

    /**
     * @throws Error
     */
    public function statistic($_, array $args): Collection | array
    {
        $statistic = TransferExchange::query();
        if (isset($args['filter'])) {
            $this->handler->__invoke($statistic, $args['filter']);
        }
        $statistic
            ->join('payment_status', 'transfer_exchanges.status_id', '=', 'payment_status.id')
            ->select([
                'payment_status.name', 'transfer_exchanges.status_id', DB::raw('count(transfer_exchanges.status_id) as count'),
            ])->groupBy(['transfer_exchanges.status_id', 'payment_status.name']);

        if (isset($args['created_at']['from']) && isset($args['created_at']['to'])) {
            $statistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
        }

        return $statistic->get();
    }

}
