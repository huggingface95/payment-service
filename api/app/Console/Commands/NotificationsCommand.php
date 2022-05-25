<?php

namespace App\Console\Commands;

use App\DTO\Email\EmailRequestDTO;
use App\DTO\Email\SendEmailRequestDTO;
use App\DTO\TransformerDTO;
use App\Jobs\SendMailJob;
use App\Models\EmailTemplate;
use App\Traits\ReplaceRegularExpressions;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;


class NotificationsCommand extends Command
{
    use ReplaceRegularExpressions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

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
        $emailTemplates = EmailTemplate::all();

        $redis = Redis::connection();

        while ($emailData = $redis->blpop('email:payment:log', 1)) {
            $emailDTO = TransformerDTO::transform(EmailRequestDTO::class, json_decode($emailData[1]));

            try {
                //TODO CHANGE to real search in template
                /** @var EmailTemplate $template */
                $template = $emailTemplates->where('id', 1)->first();
                $content = $this->replaceObjectData($template->content, $emailDTO, '/(\{\{(.*?)}})/');
                $subject = $this->replaceObjectData($template->subject, $emailDTO, '/\{\{(.*?)}}/');

                //TODO refactor SendMailJob DTO PARAMS
                Queue::later(Carbon::now()->addSecond(5), new SendMailJob(TransformerDTO::transform(SendEmailRequestDTO::class, $content, $subject)));
            } catch (\Throwable $e) {
                Log::error($e);
                continue;
            }
        }

    }
}
