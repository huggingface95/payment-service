<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\CompanyLedgerSettings;
use App\Services\CompanyRevenueAccountCommissionService;

class ProcessLedgerPeriodHistoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected CompanyLedgerSettings $ledgerSettings,
        protected string $period,
    ) {
        $this->onQueue('revenue_commission');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        CompanyRevenueAccountCommissionService $service,
    ) {
        $companyAccounts = Account::where('company_id', $this->ledgerSettings->company_id)->get();

        foreach ($companyAccounts as $account) {
            $service->calculateRevenueCommissionByPeriod($account, $this->ledgerSettings, $this->period);
        }
    }
}
