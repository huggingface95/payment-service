<?php

namespace App\Jobs;

use App\DTO\Account\IbanRequestDTO;
use App\DTO\TransformerDTO;
use App\Models\Accounts;
use Illuminate\Support\Facades\Redis;


class IbanActivationJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected IbanRequestDTO $ibanRequest;

    public function __construct(Accounts $account)
    {
        $this->ibanRequest = TransformerDTO::transform(IbanRequestDTO::class, $account);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $redis = Redis::connection();

        $redis->rpush(config('payment.redis.iban'), json_encode($this->ibanRequest));
    }
}