<?php

namespace App\Exports\ApplicantCabinet\Transfer;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;

class ApplicantCabinetTransfersExport implements WithEvents, FromView
{
    use RegistersEventListeners;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function beforeSheet(BeforeSheet $event)
    {
        $event->sheet
            ->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    }

    public static function AfterSheet(AfterSheet $event)
    {
        // All cells
        $event->sheet
            ->getStyle('A:J')
            ->getFont()
            ->setSize(8);

        // Header
        $event->sheet
            ->getDelegate()
            ->getStyle('A1:J1')
            ->getFont()
            ->setSize(13);
    }

    public function view(): View
    {
        return view('exports.applicant_cabinet.transfer.transfers', [
            'transfers' => $this->data['transfers'],
        ]);
    }
}
