<?php

namespace App\Jobs\Redis;

use App\DTO\Transfer\TransferDTO;
use App\DTO\TransformerDTO;
use App\Jobs\Job;
use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\Redis;

class TransferOutgoingJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected TransferDTO $transfer;

    public function __construct(TransferOutgoing $transfer)
    {
        $this->transfer = TransformerDTO::transform(TransferDTO::class, $transfer);
    }

    /**
     * Execute the job.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $redis = Redis::connection();

        $redis->rpush(config('payment.redis.pay'), json_encode($this->transfer));
    }
}
