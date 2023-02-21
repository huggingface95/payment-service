<?php

namespace App\Services\Account;

use App\Exceptions\GraphqlException;
use App\Http\Resources\Account\Statement\TransactionResource;
use App\Repositories\Interfaces\AccountRepositoryInterface;

class AccountStatementService
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository
    ) {
    }

    public function getAccountStatement(int $accountId, $dateFrom, $dateTo): array
    {
        $account = $this->accountRepository->findById($accountId);
        if (! $account) {
            throw new GraphqlException('Not found', 'not found', 404);
        }

        $transactions = $this->accountRepository->getTransfersForPeriodByAccountId($accountId, $dateFrom, $dateTo);
        $transactionsList = TransactionResource::collection($transactions)->sortByDesc('created_at')->jsonSerialize();

        $openningBalance = $this->accountRepository->getBalanceByAccountIdAndDate($accountId, $dateFrom);
        $closingBalance = $this->accountRepository->getBalanceByAccountIdAndDate($accountId, $dateTo);
        $debitTurnover = $this->accountRepository->getDebitTurnoverForPeriodByAccountId($accountId, $dateFrom, $dateTo);
        $creditTurnover = $this->accountRepository->getCreditTurnoverForPeriodByAccountId($accountId, $dateFrom, $dateTo);

        return [
            'account_number' => $account->account_number ?? '',
            'account_currency' => $account->currencies->code ?? '',
            'opening_balance' => $openningBalance,
            'opening_balance_date' => $dateFrom,
            'closing_balance' => $closingBalance,
            'closing_balance_date' => $dateTo,
            'debit_turnover' => $debitTurnover,
            'credit_turnover' => $creditTurnover,
            'transactions' => $transactionsList,
        ];
    }

}
