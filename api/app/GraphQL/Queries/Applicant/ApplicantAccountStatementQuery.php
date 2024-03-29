<?php

namespace App\GraphQL\Queries\Applicant;

use App\Enums\ApplicantTypeEnum;
use App\Exceptions\GraphqlException;
use App\Exports\AccountStatementExport;
use App\GraphQL\Traits\CheckAccountExistForApplicant;
use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Services\Account\AccountStatementService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

final class ApplicantAccountStatementQuery
{

    use CheckAccountExistForApplicant;

    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected AccountStatementService    $accountStatementService
    )
    {
    }


    /**
     * @throws GraphqlException
     */
    public function get($_, array $args): array
    {
        $this->checkExistsAccountById($args['account_id']);

        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        return $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);
    }

    /**
     * @throws GraphqlException
     */
    public function downloadPdf($root, array $args): array
    {
        $this->checkExistsAccountById($args['account_id']);

        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        $data = $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);

        $raw = Excel::raw(new AccountStatementExport($data), \Maatwebsite\Excel\Excel::DOMPDF);

        return [
            'base64' => base64_encode($raw),
        ];
    }

    /**
     * @throws GraphqlException
     */
    public function downloadXls($root, array $args): array
    {
        $this->checkExistsAccountById($args['account_id']);

        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        $data = $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);

        $raw = Excel::raw(new AccountStatementExport($data), \Maatwebsite\Excel\Excel::XLS);

        return [
            'base64' => base64_encode($raw),
        ];
    }


    /**
     * @throws GraphqlException
     */
    public function downloadCsv($root, array $args): array
    {
        $this->checkExistsAccountById($args['account_id']);

        $dateFrom = empty($args['created_at']['from']) ? Carbon::now()->startOfMonth() : $args['created_at']['from'];
        $dateTo = empty($args['created_at']['to']) ? Carbon::now()->endOfMonth() : $args['created_at']['to'];

        $data = $this->accountStatementService->getAccountStatement($args['account_id'], $dateFrom, $dateTo);

        $raw = Excel::raw(new AccountStatementExport($data), \Maatwebsite\Excel\Excel::CSV);

        return [
            'base64' => base64_encode($raw),
        ];
    }
}
