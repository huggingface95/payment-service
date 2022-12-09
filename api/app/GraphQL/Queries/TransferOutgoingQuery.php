<?php

namespace App\GraphQL\Queries;

use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\DB;

class TransferOutgoingQuery
{
    public function statistic($_, array $args)
    {
        $statistic = TransferOutgoing::select([
            'payment_status.name', 'status_id', DB::raw('count(status_id) as count'),
        ])
            ->join('payment_status', 'transfer_outgoings.status_id', '=', 'payment_status.id')
            ->groupBy(['status_id', 'payment_status.name']);

        if (isset($args['created_at']['from']) && isset($args['created_at']['to'])) {
            $statistic->where('created_at', '>=', $args['created_at']['from'])
                ->where('created_at', '<=', $args['created_at']['to']);
        }

        if (isset($args['company_id'])) {
            $statistic->where('company_id', $args['company_id']);
        }

        if (isset($args['payment_provider_id'])) {
            $statistic->where('payment_provider_id', $args['payment_provider_id']);
        }

        if (isset($args['account_id'])) {
            $statistic->where('account_id', $args['account_id']);
        }

        return $statistic->get();
    }
}
