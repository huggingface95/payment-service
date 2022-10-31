<?php

namespace App\Services;

use App\DTO\Requisite\RequisiteSendEmailDTO;
use App\DTO\TransformerDTO;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{

    public function getPdfRequisites(array $args)
    {
        $requisiteSendEmailDTO = TransformerDTO::transform(RequisiteSendEmailDTO::class, $args);
        $pdf = Pdf::loadHTML($requisiteSendEmailDTO->content, 'UTF-8');

        return $pdf->output();
    }

}
