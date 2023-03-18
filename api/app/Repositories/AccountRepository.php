<?php

namespace App\Repositories;

use App\Enums\OperationTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\RepositoryException;
use App\Models\Account;
use App\Models\ApplicantCompany;
use App\Models\ApplicantIndividual;
use App\Models\Transactions;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AccountRepository extends Repository implements AccountRepositoryInterface
{

    public const PARENT_CLONE_COLUMNS = [
        'owner_id',
        'payment_provider_id',
        'iban_provider_id',
        'commission_template_id',
        'payment_system_id',
        'payment_bank_id',
        'commission_template_id',
        'group_role_id',
        'group_type_id',
        'account_state_id',
        'company_id',
        'member_id',
        'project_id',
    ];

    public const PARENT_CLONE_MORPH_RECORDS = [
        'clientable' => [
            ApplicantIndividual::class => 'applicantIndividual',
            ApplicantCompany::class => 'applicantCompany',
        ]
    ];

    protected function model(): string
    {
        return Account::class;
    }

    /**
     * @throws RepositoryException
     */
    public function findById(int $id): Model|Account|null
    {
        return $this->find(['id' => $id]);
    }

    public function update(Model|Builder $model, array $data): Model|Builder
    {
        $model->updateQuietly($data);

        return $model;
    }

    public function getAccountsByPriceListFeeScheduledId(int $priceListScheduledId)
    {
        return Account::whereHas('priceListFeeScheduled', function ($query) use ($priceListScheduledId) {
            $query->where('id', $priceListScheduledId);
        })->get();
    }

    public function getAmountOfCashFlowForPeriodByAccountId(int $accountId, string $dateFrom, string $dateTo): float
    {
        $otgoingsAmount = TransferOutgoing::whereDate('execution_at', '>=', $dateFrom)
            ->whereDate('execution_at', '<=', $dateTo)
            ->where('status_id', PaymentStatusEnum::SENT->value)
            ->where('account_id', $accountId)
            ->where('operation_type_id', '!=', OperationTypeEnum::SCHEDULED_FEE->value)
            ->sum('amount');

        $incomingsAmount = TransferIncoming::whereDate('execution_at', '>=', $dateFrom)
            ->whereDate('execution_at', '<=', $dateTo)
            ->where('status_id', PaymentStatusEnum::SENT->value)
            ->where('account_id', $accountId)
            ->where('operation_type_id', '!=', OperationTypeEnum::SCHEDULED_FEE->value)
            ->sum('amount');

        return $otgoingsAmount + $incomingsAmount;
    }

    public function getTransfersForPeriodByAccountId(int $accountId, string $dateFrom, string $dateTo): Collection
    {
        $otgoings = TransferOutgoing::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('account_id', $accountId)
            ->with('sender')
            ->with('transaction')
            ->get();

        $incomings = TransferIncoming::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->where('account_id', $accountId)
            ->with('recipient')
            ->with('transaction')
            ->get();

        return $otgoings->concat($incomings);
    }

    public function getBalanceByAccountIdAndDate(int $accountId, string $date): float
    {
        $transaction = Transactions::whereDate('created_at', '<', $date)
            ->where('account_src_id', $accountId)
            ->orderBy('created_at', 'desc')
            ->first();

        return $transaction ? $transaction->balance_next : 0;
    }

    public function getDebitTurnoverForPeriodByAccountId(int $accountId, string $dateFrom, string $dateTo): float
    {
        $incomingsAmount = TransferIncoming::whereDate('execution_at', '>=', $dateFrom)
            ->whereDate('execution_at', '<=', $dateTo)
            ->where('status_id', PaymentStatusEnum::SENT->value)
            ->where('account_id', $accountId)
            ->sum('amount');

        return $incomingsAmount;
    }

    public function getCreditTurnoverForPeriodByAccountId(int $accountId, string $dateFrom, string $dateTo): float
    {
        $otgoingsAmount = TransferOutgoing::whereDate('execution_at', '>=', $dateFrom)
            ->whereDate('execution_at', '<=', $dateTo)
            ->where('status_id', PaymentStatusEnum::SENT->value)
            ->where('account_id', $accountId)
            ->sum('amount');

        return $otgoingsAmount;
    }

    /**
     * @throws RepositoryException
     */
    public function getParentClonedColumns(int $accountId): array
    {
        return array_intersect_key(
            $this->findById($accountId)->getAttributes(),
            array_flip(self::PARENT_CLONE_COLUMNS)
        );
    }

    /**
     * @throws RepositoryException
     */
    public function getParentMorphRecords(int $accountId): array
    {
        $result = [];
        $parent = $this->findById($accountId);
        foreach (self::PARENT_CLONE_MORPH_RECORDS as $relation => $types) {
            if ($record = $parent->$relation) {
                $action = $types[get_class($record)] ?? null;
                $result[$action] = $record;
            }
        }

        return $result;
    }
}
