<?php

namespace App\Services;

use App\Models\CompanyRevenueAccount;
use App\Models\Currencies;
use App\Models\PaymentSystem;
use App\Models\Transactions;
use App\Repositories\Interfaces\CompanyRevenueAccountRepositoryInterface;
use Illuminate\Support\Collection;

class CompanyRevenueAccountService
{
    protected string $format = '0000000000';

    public function __construct(protected CompanyRevenueAccountRepositoryInterface $repository)
    {
    }

    public function sync(PaymentSystem $paymentSystem, array $currencyIds): void
    {
        $company = $paymentSystem->company;

        $currencies = Currencies::query()->whereIn('id', $currencyIds)->get();

        $numbers = $this->prepareMultipleCurrencies($company->id, $currencies->pluck('id', 'code')->toArray());

        $this->repository->createMultiple($company->id, $numbers);
    }

    public function exist(int $companyId, int $currencyId): bool
    {
        $currency = Currencies::query()->find($currencyId);

        $number = $this->prepareCurrency($companyId, $currency->code);

        return $this->repository->exist($number);
    }

    private function prepareMultipleCurrencies(int $id, array $codes): array
    {
        $prepared = [];
        foreach ($codes as $code => $currencyId) {
            $prepared[] = [
                'currency_id' => $currencyId,
                'number' => $this->prepareCurrency($id, $code),
            ];
        }

        return $prepared;
    }

    private function prepareCurrency(int $id, string $code): string
    {
        $id = (string) $id;

        return sprintf('%s%s%s', strtoupper($code), substr($this->format, 0, -strlen($id)), $id);
    }

    public function addToRevenueAccountBalance(int $companyId, CompanyRevenueAccount|Collection $revenueAccount, float $amount, int $currencyId): void
    {
        $revenueBalance = $revenueAccount->balance + $amount;

        Transactions::create([
            'company_id' => $companyId,
            'transfer_id' => null,
            'transfer_type' => class_basename(TransferIncoming::class),
            'currency_src_id' => $currencyId,
            'currency_dst_id' => $currencyId,
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

    public function addToRevenueAccountBalanceByCompanyId(int $companyId, float $amount, int $currencyId): void
    {
        $revenueAccount = $this->getRevenueAccountsByCompanyId($companyId)->where('currency_id', $currencyId)->first();

        if ($revenueAccount) {
            $this->addToRevenueAccountBalance($companyId, $revenueAccount, $amount, $currencyId);
        }
    }

    public function getRevenueAccountsByCompanyId(int $companyId): Collection
    {
        return CompanyRevenueAccount::query()
            ->where('company_id', $companyId)
            ->get();
    }
}
