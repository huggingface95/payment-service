<?php

namespace App\Jobs\Redis;

use App\DTO\Account\IbanRequestDTO;
use App\DTO\TransformerDTO;
use App\Jobs\Job;
use App\Models\Account;
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
     * @return bool
     */
    public function handle(): bool
    {
        try {
            $redis = Redis::connection();
            $redis->rpush(config('payment.redis.iban.individual'), json_encode($this->ibanRequest));

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
