<?php

namespace App\DTO\Payment;


use App\DTO\TransformerDTO;
use App\Models\Payments;

class PaymentDTO
{
    public PayerDTO $payerDTO;
    public PayeeDTO $payeeDTO;
    public string $clientOrder;
    public string $currency;
    public float $amount;
    public string $description;
    public string $productName;
    public string $siteAddress;
    public string $label;
    public string $postbackUrl;
    public string $successUrl;
    public string $failUrl;


    public static function transform(Payments $payment): PaymentDTO
    {
        $dto = new self();

        $dto->payerDTO = TransformerDTO::transform(PayerDTO::class, $payment->applicantIndividual);
        $dto->payeeDTO = TransformerDTO::transform(PayeeDTO::class, $payment);
        $dto->clientOrder = 'Order';
        $dto->currency = $payment->Currencies->code;
        $dto->amount = $payment->amount;
        return $dto;
    }

}
