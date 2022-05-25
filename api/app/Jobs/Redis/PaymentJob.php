<?php

namespace App\Jobs\Redis;

use App\DTO\Payment\PaymentDTO;
use App\DTO\TransformerDTO;
use App\Jobs\Job;
use App\Models\Payments;
use Illuminate\Support\Facades\Redis;


class PaymentJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected PaymentDTO $payment;

    public function __construct(Payments $payment)
    {
        $this->payment = TransformerDTO::transform(PaymentDTO::class, $payment);
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

        $redis->rpush(config('payment.redis.pay'), json_encode($this->payment));

//        dd($redis->lrange(config('payment.redis.pay'), 0,-1));

    }
}
