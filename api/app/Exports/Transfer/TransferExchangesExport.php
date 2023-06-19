<?php

namespace App\Exports\Transfer;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransferExchangesExport implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.transfer.transfers_exchange', [
            'transfers' => $this->data['transfers'],
        ]);
    }
}
