<?php

namespace App\Jobs;

use App\DTO\Account\IbanRequestDTO;
use App\DTO\TransformerDTO;
use App\Models\Account;
use App\Models\AccountState;
use GuzzleHttp\Client;

class IbanCompanyActivationJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected Account $account;

    protected IbanRequestDTO $ibanRequest;

    public function __construct(Account $account)
    {
        $this->account = $account;
        $this->ibanRequest = TransformerDTO::transform(IbanRequestDTO::class, $account);
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Client $client)
    {
        $response = $client->get('cl-junc-apicore:2490/clearjunction/iban-company/check?'.http_build_query(['clientCustomerId' => $this->ibanRequest->id]));

        if ($response->getStatusCode() == 200) {
            $this->account->account_state = AccountState::WAITING_FOR_ACCOUNT_GENERATION;
            $this->account->save();
        }
    }
}
