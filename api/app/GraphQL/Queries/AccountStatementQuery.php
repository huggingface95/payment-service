<?php

namespace App\GraphQL\Queries;

use App\Exports\AccountStatementExport;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Services\Account\AccountStatementService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder;

final class AccountStatementQuery
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private AccountStatementService $accountStatementService,
    ) {
    }

    public function get($_, array $args): array
    {
        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        return $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);
    }

    /**
     * Get data with pagination
     *
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function getTransactions($_, array $args): array
    {
        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        $result = $this->accountStatementService->getAccountStatementTransactions($args['account_id'], $dateFrom, $dateTo);

        $data = $this->paginateQuery($result, $args['count'] ?? env('PAGINATE_DEFAULT_COUNT'), $args['page'] ?? 1);

        return [
            'data' => $data->items(),
            'paginatorInfo' => [
                'count' => $data->count(),
                'currentPage' => $data->currentPage(),
                'firstItem' => $data->firstItem(),
                'hasMorePages' => $data->hasMorePages(),
                'lastItem' => $data->lastItem(),
                'lastPage' => $data->lastPage(),
                'perPage' => $data->perPage(),
                'total' => $data->total(),
            ]
        ];
    }

    public function downloadPdf($root, array $args): array
    {
        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        $data = $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);

        $raw = Excel::raw(new AccountStatementExport($data), \Maatwebsite\Excel\Excel::DOMPDF);

        return [
            'base64' => base64_encode($raw),
        ];
    }

    public function downloadXls($root, array $args): array
    {
        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        $data = $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);

        $raw = Excel::raw(new AccountStatementExport($data), \Maatwebsite\Excel\Excel::XLS);

        return [
            'base64' => base64_encode($raw),
        ];
    }

    public function downloadCsv($root, array $args): array
    {
        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        $data = $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);

        $raw = Excel::raw(new AccountStatementExport($data), \Maatwebsite\Excel\Excel::CSV);

        return [
            'base64' => base64_encode($raw),
        ];
    }

    private function paginateQuery($data, $count, $page): LengthAwarePaginator
    {
        $perPage = $count;
        $currentPage = $page;
        $total = count($data);

        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($data, $offset, $perPage);

        return new LengthAwarePaginator($items, $total, $perPage, $currentPage);
    }
}
