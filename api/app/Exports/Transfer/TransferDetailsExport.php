<?php

namespace App\Exports\Transfer;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransferDetailsExport implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.transfer.transfer_details', [
            'created_date' => $this->data['created_date'],
            'transfer_id' => $this->data['transfer_id'],
            'operation_type' => $this->data['operation_type'],
            'execution_date' => $this->data['execution_date'],
            'amount' => $this->data['amount'],
            'currency' => $this->data['currency'],
            'account_id' => $this->data['account_id'],
            'account_client_name' => $this->data['account_client_name'],
            'iban' => $this->data['iban'],
            'payment_provider_fee' => $this->data['payment_provider_fee'],
            'fee_amount' => $this->data['fee_amount'],
            'final_amount_debited' => $this->data['final_amount_debited'],
            'fee_account' => $this->data['fee_account'],
            'urgency' => $this->data['urgency'],
            'transfer_reason' => $this->data['transfer_reason'],
            'status' => $this->data['status'],
        ]);
    }
}
