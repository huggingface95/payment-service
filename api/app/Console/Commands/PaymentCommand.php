<?php

namespace App\Console\Commands;


use App\DTO\Payment\PaymentDTO;
use App\DTO\TransformerDTO;
use App\Jobs\PaymentJob;
use App\Models\Payments;
use App\Traits\ReplaceRegularExpressions;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;


class PaymentCommand extends Command
{
    use ReplaceRegularExpressions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payIn:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send PayIn';

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
        //TODO change to real select query
        $payments = Payments::query()->get()->where('id', 1);

        foreach ($payments as $payment) {
            try {
//                dispatch(new PaymentJob(TransformerDTO::transform(PaymentDTO::class, $payment)));
                Queue::later(Carbon::now()->addSecond(5), new PaymentJob(TransformerDTO::transform(PaymentDTO::class, $payment)));
            } catch (\Throwable $e) {
                Log::error($e);
                continue;
            }
        }


    }
}
