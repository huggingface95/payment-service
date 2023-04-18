<?php

namespace App\Services;

use App\Enums\PeriodEnum;
use App\Models\Account;
use App\Models\CompanyLedgerDayHistory;
use App\Models\CompanyLedgerSettings;
use App\Models\CompanyRevenueAccount;
use App\Models\Fee;
use App\Models\Transactions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyRevenueAccountCommissionService extends AbstractService
{
    public function calculateRevenueCommissionByPeriod(Account $account, CompanyLedgerSettings $ledgerSettings, string $period): void
    {
        $dateInterval = $this->getCommissionDateInterval($account, $ledgerSettings, $period);
        $revenueAccounts = $this->getRevenueAccountsByCompanyId($account->company_id);

        if (empty($ledgerSettings->end_of_day_time) || $period === PeriodEnum::DAY->value) {
            $amountByCurrency = $this->getFeeByCurrency($account, $dateInterval);
        } else {
            $amountByCurrency = $this->getAmountByCurrency($account, $dateInterval);
        }

        $amountByCurrency->each(function ($amount, $currency_id) use ($period, $account, $dateInterval, $revenueAccounts, $ledgerSettings) {
            DB::beginTransaction();

            try {
                $revenueAccount = $revenueAccounts->where('currency_id', $currency_id)->first();
                $revenueBalance = $revenueAccount->balance;

                if ($this->isAllowToAddBalance($ledgerSettings, $period)) {
                    $this->addToRevenueAccountBalance($account, $revenueAccount, $amount, $currency_id);
                    $revenueBalance += $amount;
                }

                $model = 'App\\Models\\CompanyLedger' . $period . 'History';
                $model::firstOrCreate([
                    'account_id' => $account->id,
                    'revenue_account_id' => $revenueAccount->id,
                    'company_id' => $account->company_id,
                    'currency_id' => $currency_id,
                    'amount' => $amount,
                    'revenue_balance' => $revenueBalance,
                    'created_at' => $dateInterval['end_date_time'],
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                Log::error($e->getMessage());
            }
        });
    }

    private function addToRevenueAccountBalance(Account $account, CompanyRevenueAccount $revenueAccount, float $amount, int $currency_id): void
    {
        $revenueBalance = $revenueAccount->balance + $amount;

        Transactions::create([
            'company_id' => $account->company_id,
            'transfer_id' => null,
            'transfer_type' => class_basename(TransferIncoming::class),
            'currency_src_id' => $currency_id,
            'currency_dst_id' => $currency_id,
            'account_src_id' => null,
            'account_dst_id' => null,
            'revenue_account_id' => $revenueAccount->id,
            'balance_prev' => $revenueAccount->balance,
            'balance_next' => $revenueBalance,
            'amount' => $amount,
            'txtype' => 'revenue',
        ]);

        $revenueAccount->update([
            'balance' => $revenueBalance,
        ]);
    }

    private function isAllowToAddBalance(CompanyLedgerSettings $ledgerSettings, string $period): bool
    {
        if (!empty($ledgerSettings->end_of_day_time) && $period === PeriodEnum::DAY->value) {
            return true;
        } else if (empty($ledgerSettings->end_of_day_time) && $period === PeriodEnum::WEEK->value) {
            return true;
        } else if (empty($ledgerSettings->end_of_day_time) && empty($ledgerSettings->end_of_week_time) && $period === PeriodEnum::MONTH->value) {
            return true;
        }

        return false;
    }

    private function getCommissionDateInterval(Account $account, CompanyLedgerSettings $ledgerSettings, string $period = null): array
    {
        $period = $period ?? PeriodEnum::DAY->value;
        $model = 'App\\Models\\CompanyLedger' . $period . 'History';
        $lastCommission = $model::query()
            ->where('account_id', $account->id)
            ->orderBy('id', 'desc')
            ->first();

        $startDateTime = $lastCommission?->created_at->toDateTimeString() ?? $account->created_at->toDateTimeString();
        $endDateTime = $ledgerSettings->{'end_of_' . strtolower($period) . '_time'}?->toDateTimeString();

        return [
            'start_date_time' => $startDateTime,
            'end_date_time' => $endDateTime,
        ];
    }

    private function getAmountByCurrency(Account $account, array $dateInterval)
    {
        $history = CompanyLedgerDayHistory::query()
            ->where('account_id', $account->id)
            ->where('created_at', '>=', $dateInterval['start_date_time'])
            ->where('created_at', '<=', $dateInterval['end_date_time'])
            ->get();

        return $history->groupBy('currency_id')->map(function ($item) {
            return $item->sum('amount');
        });
    }

    private function getFeeByCurrency(Account $account, array $dateInterval)
    {
        $fees = Fee::query()
            ->where('account_id', $account->id)
            ->where('created_at', '>=', $dateInterval['start_date_time'])
            ->where('created_at', '<=', $dateInterval['end_date_time'])
            ->get();

        return $fees->groupBy('transfer.currency_id')->map(function ($item) {
            return $item->sum('fee');
        });
    }

    private function getRevenueAccountsByCompanyId(int $companyId): Collection
    {
        return CompanyRevenueAccount::query()
            ->where('company_id', $companyId)
            ->get();
    }
}
