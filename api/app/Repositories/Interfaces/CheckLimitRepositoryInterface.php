<?php

namespace App\Repositories\Interfaces;

use App\DTO\Service\CheckLimitDTO;
use App\Models\Account;
use App\Models\Payments;
use App\Models\TransferOutgoing;
use Illuminate\Support\Collection;

interface CheckLimitRepositoryInterface
{
    public function getAllProcessedAmount(CheckLimitDTO $checkLimitDTO): Collection;

    public function getAllLimits(CheckLimitDTO $checkLimitDTO): Collection;

    public function createReachedLimit(Account $account, $limit): void;

    public function getAllPaymentProcessedAmount(Payments $payment): Collection;

    public function getAllTransferOutgoingProcessedAmount(TransferOutgoing $transfer): Collection;
}
