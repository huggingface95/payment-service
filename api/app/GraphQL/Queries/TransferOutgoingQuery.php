<?php

namespace App\GraphQL\Queries;

use App\Exceptions\GraphqlException;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\ExportService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransferOutgoingQuery
{
    public function __construct(
        protected ExportService $exportService,
        protected TransferOutgoingRepositoryInterface $transferRepository,
    ) {
    }

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

        if (isset($args['id'])) {
            $statistic->where('transfer_outgoings.id', $args['id']);
        }

        return $statistic->get();
    }

    /**
     * @throws GraphqlException
     */
    public function downloadDetails($_, array $args): array
    {
        $transfer = $this->transferRepository->findById($args['id']);
        if (! $transfer) {
            throw new GraphqlException('Transfer not found', 'not found', Response::HTTP_NOT_FOUND);
        }

        $raw = $this->exportService->exportTransferDetails($transfer, $args['type']);

        return [
            'base64' => base64_encode($raw),
        ];
    }
}
