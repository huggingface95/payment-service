<?php

namespace App\Services;

use App\Exports\Transfer\TransferDetailsExport;
use App\Exports\Transfer\TransferIncomingsExport;
use App\Exports\Transfer\TransferOutgoingsExport;
use App\Http\Resources\Transfer\TransferOutgoingDetailsResource;
use App\Http\Resources\Transfer\TransfersListResource;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExportService extends AbstractService
{
    public function exportTransferDetails($transfer, $type): string
    {
        $type = $this->getTypeOfFile($type);

        if ($transfer instanceof TransferOutgoing) {
            $data = TransferOutgoingDetailsResource::make($transfer)->jsonSerialize();
        } elseif ($transfer instanceof TransferIncoming) {
            $data = TransferOutgoingDetailsResource::make($transfer)->jsonSerialize();
        } else {
            throw new \Exception('Model not found');
        }
        
        return Excel::raw(new TransferDetailsExport($data), $type);
    }

    public function exportTransfersList($model, $data, $type): string
    {
        $type = $this->getTypeOfFile($type);

        if ($model == class_basename(TransferOutgoing::class)) {
            $exportData = $this->exportTransferOutgoings($data);
        } elseif ($model == class_basename(TransferIncoming::class)) {
            $exportData = $this->exportTransferIncomings($data);
        } else {
            throw new \Exception('Model not found');
        }

        return Excel::raw($exportData, $type);
    }

    private function exportTransferIncomings($data): TransferIncomingsExport
    {
        $transfersList = $this->collectTransfers($data);
        $transfersList = TransfersListResource::collection($transfersList ?? [])->sortByDesc('created_at')->jsonSerialize();

        return new TransferIncomingsExport(['transfers' => $transfersList]);
    }

    private function exportTransferOutgoings($data): TransferOutgoingsExport
    {
        $transfersList = $this->collectTransfers($data);
        $transfersList = TransfersListResource::collection($transfersList ?? [])->sortByDesc('created_at')->jsonSerialize();

        return new TransferOutgoingsExport(['transfers' => $transfersList]);
    }

    private function collectTransfers(Collection $transfers): Collection
    {
        $transfersList = [];
        foreach ($transfers as $transfer) {
            $transfersList[] = $transfer;

            foreach ($transfer->fees()->get() as $fee) {
                $transfersList[] = $fee;
            }
        }

        return collect($transfersList);
    }

    private function getTypeOfFile(string $type): string
    {
        if ($type == 'Pdf') {
            return \Maatwebsite\Excel\Excel::DOMPDF;
        } elseif ($type == 'Xls') {
            return \Maatwebsite\Excel\Excel::XLS;
        } else {
            return \Maatwebsite\Excel\Excel::CSV;
        }
    }
}
