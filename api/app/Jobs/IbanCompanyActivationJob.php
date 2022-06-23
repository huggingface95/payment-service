<?php

namespace App\Jobs;

use App\DTO\Account\IbanRequestDTO;
use App\DTO\TransformerDTO;
use App\Models\Accounts;
use App\Models\AccountState;
use GuzzleHttp\Client;

class IbanCompanyActivationJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected Accounts $account;

    protected IbanRequestDTO $ibanRequest;

    public function __construct(Accounts $account)
    {
        $this->account = $account;
        $this->ibanRequest = TransformerDTO::transform(IbanRequestDTO::class, $account);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Client $client)
    {
        $response = $client->get('cl-junc-apicore:8080/clearjunction/iban-compnay/check?'.http_build_query(['clientCustomerId' => $this->ibanRequest->id]));

        if ($response->getStatusCode() == 200) {
            $this->account->account_state = AccountState::WAITING_IBAN_ACTIVATION;
            $this->account->save();
        }
    }
}
