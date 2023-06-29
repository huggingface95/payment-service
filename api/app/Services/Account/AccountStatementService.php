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

    public function getAccountStatementTransactions(int $accountId, $dateFrom, $dateTo): array
    {
        $account = $this->accountRepository->findById($accountId);
        if (! $account) {
            throw new GraphqlException('Not found', 'not found', 404);
        }

        $transactions = $this->accountRepository->getTransfersForPeriodByAccountId($accountId, $dateFrom, $dateTo);
        $transactionsList = TransactionResource::collection($transactions)->sortByDesc('created_at')->jsonSerialize();

        $data = [];
        foreach ($transactionsList as $transaction) {
            $data[] = [
                'transaction_id' => $transaction['transaction_id'],
                'created_at' => $transaction['created_at'],
                'sender_recipient' => $transaction['sender_recipient'],
                'reason' => $transaction['reason'],
                'amount' => $transaction['amount'],
                'account_number' => $transaction['account_number'],
                'account_client' => $transaction['account_client'],
                'status' => $transaction['status'],
                'account_balance' => $transaction['account_balance'],
                'transfer_type' => $transaction['transfer_type'],
            ];
        }

        return $data;
    }
}
