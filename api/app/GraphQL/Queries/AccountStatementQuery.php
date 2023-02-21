<?php

namespace App\GraphQL\Queries;

use App\Exports\AccountStatementExport;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Services\Account\AccountStatementService;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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
}
