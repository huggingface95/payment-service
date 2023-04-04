<?php

namespace App\Services;

use App\Models\Account;
use App\Models\CompanyLedgerDayHistory;
use App\Models\CompanyLedgerSettings;
use App\Models\CompanyRevenueAccount;
use App\Models\Fee;
use Illuminate\Support\Collection;

class CompanyRevenueAccountCommissionService extends AbstractService
{

    public function calculateRevenueCommission(Account $account, CompanyLedgerSettings $ledgerSettings): float
    {
        $dateInterval = $this->getCommissionDateInterval($account, $ledgerSettings);

        $fees = Fee::query()
            ->where('account_id', $account->id)
            ->where('created_at', '>=', $dateInterval['start_date_time'])
            ->where('created_at', '<=', $dateInterval['end_date_time'])
            ->get();

        $totalCommission = $fees->sum('fee');

        $revenueAccounts = $this->getRevenueAccountsByCompanyId($account->company_id);
        
        // currency_id and sum of fee
        $feesByCurrency = $fees->groupBy('transfer.currency_id')->map(function ($item) {
            return $item->sum('fee');
        });

        $feesByCurrency->each(function ($amount, $currency_id) use ($account, $dateInterval, $revenueAccounts) {
            $revenueAccount = $revenueAccounts
                ->where('currency_id', $currency_id)
                ->where('company_id', $account->company_id)
                ->first();

            CompanyLedgerDayHistory::create([
                'account_id' => $account->id,
                'revenue_account_id' => $revenueAccount->id,
                'company_id' => $account->company_id,
                'currency_id' => $currency_id,
                'amount' => $amount,
                'created_at' => $dateInterval['end_date_time'],
            ]);
        });

        return $totalCommission;
    }

    private function getCommissionDateInterval(Account $account, CompanyLedgerSettings $ledgerSettings): array
    {
        $lastCommission = CompanyLedgerDayHistory::query()
            ->where('account_id', $account->id)
            ->orderBy('id', 'desc')
            ->first();

        return [
            'start_date_time' => $lastCommission?->created_at ?? $account->created_at->toDateTimeString(),
            'end_date_time' => $ledgerSettings->end_of_day_time->toDateTimeString(),
        ];
    }

    private function getRevenueAccountsByCompanyId(int $companyId): Collection
    {
        return CompanyRevenueAccount::query()
            ->where('company_id', $companyId)
            ->get();
    }
}
