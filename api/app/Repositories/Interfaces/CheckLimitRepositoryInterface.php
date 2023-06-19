<?php

namespace App\Repositories\Interfaces;

use App\DTO\Service\CheckLimitDTO;
use App\Models\Account;
use App\Models\TransferOutgoing;
use Illuminate\Support\Collection;

interface CheckLimitRepositoryInterface
{
    public function getAllProcessedAmount(CheckLimitDTO $checkLimitDTO): Collection;

    public function getAllLimits(CheckLimitDTO $checkLimitDTO): Collection;

    public function createReachedLimit(Account $account, $limit): void;

    public function getAllTransferOutgoingProcessedAmount(TransferOutgoing $transfer, string $clientType): Collection;

    public function getAllTransferIncomingProcessedAmount(TransferOutgoing $transfer, string $clientType): Collection;
}
