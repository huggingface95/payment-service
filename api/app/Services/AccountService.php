<?php

namespace App\Services;

use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\AccountRepositoryInterface;

class AccountService extends AbstractService
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository
    ) {
    }

    public function addToBalance(Account $account, float $amount): void
    {
        $currentBalance = $account->current_balance + $amount;

        /** @var Account $account */
        $this->accountRepository->update($account, [
            'current_balance' => $currentBalance,
            'available_balance' => $currentBalance - $account->reserved_balance,
        ]);
    }

    /**
     * @throws GraphqlException
     */
    public function withdrawFromBalance(TransferOutgoing $transfer): void
    {
        $account = $transfer->account;

        if ($account->available_balance < $transfer->amount_debt) {
            throw new GraphqlException('Available balance less than payment amount', 'use');
        }

        try {
            /** @var Account $account */
            $this->accountRepository->update($account, [
                'current_balance' => $account->current_balance - $transfer->amount_debt,
                'available_balance' => $account->available_balance - $transfer->amount_debt,
            ]);

        } catch (\Exception $e) {
            throw new GraphqlException('Failed to update account balance', 'use');
        }
    }

    /**
     * @throws GraphqlException
     */
    public function withdrawFromReservedBalance(TransferOutgoing $transfer): void
    {
        $account = $transfer->account;
        $amount = $transfer->amount_debt;

        if ($account->reserved_balance < $amount) {
            throw new GraphqlException('Reserved balance less than payment amount', 'use');
        }

        $account->current_balance = $account->current_balance - $amount;
        $account->reserved_balance = $account->reserved_balance - $amount;
        $account->available_balance = $account->current_balance - $account->reserved_balance;
        $account->save();
    }

    public function setAmmountReserveOnAccountBalance(TransferOutgoing $transfer): Account
    {
        $account = Account::findOrFail($transfer->account_id);

        if ($account->available_balance < $transfer->amount_debt) {
            throw new GraphqlException('Available balance less than payment amount', 'use');
        }

        /** @var Account $account */
        $account = $this->accountRepository->update($account, [
            'reserved_balance' => $account->reserved_balance + $transfer->amount_debt,
            'available_balance' => $account->available_balance - $transfer->amount_debt,
        ]);

        return $account;
    }

    public function unsetAmmountReserveOnAccountBalance(TransferOutgoing $transfer): Account
    {
        $account = $this->accountRepository->findById($transfer->account_id);

        if ($account->reserved_balance < $transfer->amount_debt) {
            throw new GraphqlException('Reserved balance less than payment amount', 'use');
        }

        /** @var Account $account */
        $account = $this->accountRepository->update($account, [
            'reserved_balance' => $account->reserved_balance - $transfer->amount_debt,
            'available_balance' => $account->available_balance + $transfer->amount_debt,
        ]);

        return $account;
    }

    public function cloneParentAccountColumns(Account $account, int $parentId): void
    {
        $columns = $this->accountRepository->getParentClonedColumns($parentId);
        foreach ($columns as $k => $v) {
            $account->{$k} = $v;
        }
    }

    public function cloneParentAccountMorphRecords(Account $account, int $parentId): void
    {
        $morphRecords = $this->accountRepository->getParentMorphRecords($parentId);
        foreach ($morphRecords as $action => $record) {
            $account->{$action}()->attach($record);
        }
    }
}
