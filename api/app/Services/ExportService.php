<?php

namespace App\Services;

use App\Exports\Account\AccountsExport;
use App\Exports\Applicant\ApplicantCompaniesExport;
use App\Exports\Applicant\ApplicantIndividualsExport;
use App\Exports\Transfer\TransferDetailsExport;
use App\Exports\Transfer\TransferIncomingsExport;
use App\Exports\Transfer\TransferOutgoingsExport;
use App\Http\Resources\Account\AccountsListResource;
use App\Http\Resources\Applicant\ApplicantCompaniesListResource;
use App\Http\Resources\Applicant\ApplicantIndividualsListResource;
use App\Http\Resources\Transfer\TransferOutgoingDetailsResource;
use App\Http\Resources\Transfer\TransfersListResource;
use App\Models\TransferIncoming;
use App\Models\TransferOutgoing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExportService extends AbstractService
{
    public function exportTransferDetails(TransferOutgoing|TransferIncoming $transfer, string $type): string
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

    public function exportListByModelName(string $model, $data, string $type): string
    {
        $type = $this->getTypeOfFile($type);

        $method = 'export' . $model;
        if (method_exists($this, $method)) {
            $exportData = $this->{$method}($data);
        } else {
            throw new \Exception('Model not found');
        }

        return Excel::raw($exportData, $type);
    }

    private function exportAccount(Collection $data): AccountsExport
    {
        $accounts = AccountsListResource::collection($data)->sortByDesc('created_at')->jsonSerialize();

        return new AccountsExport(['accounts' => $accounts]);
    }

    private function exportApplicantIndividual(Collection $data): ApplicantIndividualsExport
    {
        $applicants = ApplicantIndividualsListResource::collection($data)->sortByDesc('created_at')->jsonSerialize();

        return new ApplicantIndividualsExport(['applicants' => $applicants]);
    }

    private function exportApplicantCompany(Collection $data): ApplicantCompaniesExport
    {
        $companies = ApplicantCompaniesListResource::collection($data)->sortByDesc('created_at')->jsonSerialize();

        return new ApplicantCompaniesExport(['companies' => $companies]);
    }

    private function exportTransferIncoming( $data): TransferIncomingsExport
    {
        $transfersList = $this->collectTransfers($data);
        $transfersList = TransfersListResource::collection($transfersList ?? [])->sortByDesc('created_at')->jsonSerialize();

        return new TransferIncomingsExport(['transfers' => $transfersList]);
    }

    private function exportTransferOutgoing( $data): TransferOutgoingsExport
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
