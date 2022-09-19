<?php

namespace App\Jobs\Redis;

use App\DTO\Account\IbanRequestDTO;
use App\DTO\TransformerDTO;
use App\Exceptions\GraphqlException;
use App\Jobs\Job;
use App\Models\Account;
use App\Models\AccountState;
use Illuminate\Support\Facades\Redis;

class IbanIndividualActivationJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected IbanRequestDTO $ibanRequest;

    public function __construct(Account $account)
    {
        $this->ibanRequest = TransformerDTO::transform(IbanRequestDTO::class, $account);
    }

    /**
     * @throws GraphqlException
     */
    public function handle()
    {
        /** @var Account $account */
        $account = Account::findOrFail($this->ibanRequest->id);
        $account->account_state_id = AccountState::AWAITING_ACCOUNT;
        $account->save();

        $redis = Redis::connection();
        $redis->rpush(config('payment.redis.iban.individual'), json_encode($this->ibanRequest));
    }
}
