<?php

namespace App\Console\Commands;

use App\DTO\Account\IbanResponseDTO;
use App\DTO\TransformerDTO;
use App\Models\EmailTemplate;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;


class IbanGenerateCommand extends Command
{
    use ReplaceRegularExpressions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iban:generate';

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

        while ($ibanData = $redis->blpop('iban:generate:log', 1)) {
            $ibanDTO = TransformerDTO::transform(IbanResponseDTO::class, json_decode($ibanData[1]));

            try {
                continue;
//                $account = Accounts::


//                Queue::later(Carbon::now()->addSecond(5), new SendMailJob(TransformerDTO::transform(SendEmailRequestDTO::class, $content, $subject)));
            } catch (\Throwable $e) {
                Log::error($e);
                continue;
            }
        }

    }
}
