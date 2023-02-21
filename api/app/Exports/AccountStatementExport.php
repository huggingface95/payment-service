<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccountStatementExport implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.account_statement', [
            'account_number' => $this->data['account_number'],
            'account_currency' => $this->data['account_currency'],
            'opening_balance' => $this->data['opening_balance'],
            'opening_balance_date' => $this->data['opening_balance_date'],
            'debit_turnover' => $this->data['debit_turnover'],
            'credit_turnover' => $this->data['credit_turnover'],
            'closing_balance' => $this->data['closing_balance'],
            'closing_balance_date' => $this->data['closing_balance_date'],
            'transactions' => $this->data['transactions'],
        ]);
    }
}
