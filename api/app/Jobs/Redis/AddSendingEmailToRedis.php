<?php

namespace App\Jobs\Redis;

use App\Jobs\Job;
use App\Models\EmailSmtp;
use Illuminate\Support\Facades\Redis;

class AddSendingEmailToRedis extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected EmailSmtp $smtp;

    public function __construct(EmailSmtp $emailSmtp)
    {
        $this->smtp = $emailSmtp;
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
        $redis->rpush(config('mail.redis.job'), json_encode($this->smtp));
    }
}
