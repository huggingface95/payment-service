<?php

namespace App\Services;

use App\Exceptions\GraphqlException;
use App\Models\Payments;

class AccountService extends AbstractService
{

    public function __construct(protected PaymentsService $paymentsService)
    {
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

}
