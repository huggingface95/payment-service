<?php

namespace App\Services;

use App\Exceptions\GraphqlException;
use App\Models\Account;
use App\Models\Payments;
use App\Models\TransferOutgoing;
use App\Repositories\Interfaces\AccountRepositoryInterface;

class AccountService extends AbstractService
{
    public function __construct(
        protected PaymentsService $paymentsService,
        protected AccountRepositoryInterface $accountRepository
    ) {
    }

    public function withdrawFromBalance(Payments $payment)
    {
        $account = $payment->account;
        $amount = $this->paymentsService->getAccountAmountRealWithCommission($payment, $payment->fee);

        if ($account->reserved_balance < $amount) {
            throw new GraphqlException('Reserved balance less than payment amount', 'use');
        }

        $account->current_balance = $account->current_balance - $amount;
        $account->reserved_balance = $account->reserved_balance - $amount;
        $account->available_balance = $account->current_balance - $account->reserved_balance;
        $account->save();
    }

    public function setAmmountReserveOnBalance(Payments $payment)
    {
        $account = $payment->account;
        $amount = $this->paymentsService->getAccountAmountRealWithCommission($payment, $payment->fee);

        if ($account->available_balance < $amount) {
            throw new GraphqlException('Available balance less than payment amount', 'use');
        }

        $account->reserved_balance = $account->reserved_balance + $amount;
        $account->available_balance = $account->current_balance - $account->reserved_balance;
        $account->save();
    }

    public function unsetAmmountReserveOnBalance(Payments $payment)
    {
        $account = $payment->account;
        $amount = $this->paymentsService->getAccountAmountRealWithCommission($payment, $payment->fee);

        if ($account->reserved_balance < $amount) {
            throw new GraphqlException('Reserved balance less than payment amount', 'use');
        }

        $account->reserved_balance = $account->reserved_balance - $amount;
        $account->available_balance = $account->current_balance - $account->reserved_balance;
        $account->save();
    }

    public function setAmmountReserveOnAccountBalance(TransferOutgoing $transfer): Account
    {
        $account = $this->accountRepository->findById($transfer->account_id);

        if ($account->available_balance < $transfer->amount_debt) {
            throw new GraphqlException('Available balance less than payment amount', 'use');
        }

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

        $account = $this->accountRepository->update($account, [
            'reserved_balance' => $account->reserved_balance - $transfer->amount_debt,
            'available_balance' => $account->available_balance + $transfer->amount_debt,
        ]);

        return $account;
    }
}
