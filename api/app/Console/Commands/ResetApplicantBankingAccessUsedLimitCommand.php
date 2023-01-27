<?php

namespace App\Console\Commands;

use App\Models\ApplicantBankingAccess;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ResetApplicantBankingAccessUsedLimitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applicant-bancking-access:reset-used-limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset used limits for applicant banking access';

    /**
     * Create a new command instance.
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
        ApplicantBankingAccess::where('used_daily_limit', '>', 0)->update([
            'used_daily_limit' => 0,
        ]);

        if (Carbon::today()->startOfMonth()) {
            ApplicantBankingAccess::where('used_monthly_limit', '>', 0)->update([
                'used_monthly_limit' => 0,
            ]);
        }
    }
}
