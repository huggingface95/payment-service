<?php

namespace App\Services;


use App\Models\Currencies;
use App\Models\PaymentSystem;
use App\Repositories\Interfaces\CompanyRevenueAccountRepositoryInterface;

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
                'number' => $this->prepareCurrency($id, $code)
            ];
        }

        return $prepared;
    }

    private function prepareCurrency(int $id, string $code): string
    {
        $id = (string)$id;

        return sprintf("%s%s%s", strtoupper($code), substr($this->format, 0, -strlen($id)), $id);
    }

}
