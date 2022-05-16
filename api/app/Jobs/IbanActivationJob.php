<?php

namespace App\Jobs;

use App\DTO\Account\IbanRequestDTO;
use GuzzleHttp\Client;



class IbanActivationJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected IbanRequestDTO $ibanRequestDTO;

    public function __construct(IbanRequestDTO $ibanRequestDTO)
    {
        $this->ibanRequestDTO = $ibanRequestDTO;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Client $client)
    {
        $response = $client->post(
            'cl-junc-apicore:8080/generate',
            [
                'body' => json_encode($this->ibanRequestDTO)
            ]
        );


        dd($response);

    }
}
