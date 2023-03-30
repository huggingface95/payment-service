<?php

namespace App\Repositories\Interfaces;

interface CompanyRevenueAccountRepositoryInterface
{
    public function createMultiple(int $companyId, array $numbers): void;

    public function exist($code): bool;
}
