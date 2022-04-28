<?php

namespace App\Jobs;

use App\DTO\Payment\PaymentDTO;
use App\DTO\TransformerDTO;
use App\Models\Payments;
use GuzzleHttp\Client;



class PaymentJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected PaymentDTO $payment;

    public function __construct(PaymentDTO $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Client $client)
    {

        $payment = Payments::with('applicantIndividual')->find(1);


        $dto = TransformerDTO::transform(PaymentDTO::class, $payment);

        $response = $client->post(
            'cl-junc-apicore:8080/payin/invoice',
            [
                'body' => json_encode($dto)
            ]
        );


        dd($response);

    }
}
