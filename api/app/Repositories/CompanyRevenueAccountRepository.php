<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\CompanyRevenueAccount;
use App\Repositories\Interfaces\CompanyRevenueAccountRepositoryInterface;

class CompanyRevenueAccountRepository extends Repository implements CompanyRevenueAccountRepositoryInterface
{

    protected function model(): string
    {
        return CompanyRevenueAccount::class;
    }

    /**
     * @throws RepositoryException
     */
    public function createMultiple(int $companyId, array $numbers): void
    {
        foreach ($numbers as $number) {
            $this->query()->firstOrCreate(
                ['number' => $number['number']],
                ['company_id' => $companyId, 'currency_id' => $number['currency_id']]
            );
        }
    }

    /**
     * @throws RepositoryException
     */
    public function exist($code): bool
    {
        return $this->query()->where('number', $code)->exists();
    }

}
