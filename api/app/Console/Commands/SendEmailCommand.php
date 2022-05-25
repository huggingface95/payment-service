<?php

namespace App\Console\Commands;

use App\DTO\Email\SmtpConfigDTO;
use App\DTO\Email\SmtpDataDTO;
use App\DTO\Email\SmtpUserDTO;
use App\DTO\TransformerDTO;
use App\Jobs\SendMailJob;
use App\Models\EmailTemplate;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;


class SendEmailCommand extends Command
{
    use ReplaceRegularExpressions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smtp:email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $redis = Redis::connection();

        while ($emailData = $redis->blpop(config('mail.redis.job'), 1)) {
            try {
                $emailData = TransformerDTO::transform(SmtpUserDTO::class, json_decode($emailData[1]));
                /** @var EmailTemplate $emailTemplate */
                $emailTemplate = EmailTemplate::with('member.smtp')->find($emailData->templateId);
                $content = $this->replaceObjectData($emailTemplate->getHtml(), (object)['message' => $emailData->message], '/(\{\{(.*?)}})/');
                $subject = $this->replaceObjectData($emailTemplate->subject, (object)['subject' => 'subject'], '/\{\{(.*?)}}/');
                $data = TransformerDTO::transform(SmtpDataDTO::class, $emailTemplate->member->smtp, $content, $subject);
                $config = TransformerDTO::transform(SmtpConfigDTO::class, $emailTemplate->member->smtp);
                dispatch(new SendMailJob($config, $data));
//                Queue::later(Carbon::now()->addSecond(30), new SendMailJob($config, $data));
            } catch (\Throwable $e) {
                Log::error($e);
                continue;
            }
        }
    }
}
