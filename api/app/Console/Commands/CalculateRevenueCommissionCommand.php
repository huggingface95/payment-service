<?php

namespace App\Console\Commands;

use App\Jobs\ProcessLedgerDayHistoryJob;
use App\Models\CompanyLedgerSettings;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CalculateRevenueCommissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:calculate-revenue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Revenue commission for companies';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected TransferOutgoingRepositoryInterface $transferRepository,
        protected TransferOutgoingService $transferService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chunkSize = 100;
        $timeNow = Carbon::now()->format('H:i:s');

        $this->info('Chunk size: ' . $chunkSize);
        $this->info('Time now: ' . $timeNow);

        $query = CompanyLedgerSettings::query()->where('end_of_day_time', '<=', $timeNow);

        $query->chunkById($chunkSize, function (Collection $settings) {
            foreach ($settings as $setting) {
                $this->info('Processing for company: ' . $setting->company_id);

                dispatch(new ProcessLedgerDayHistoryJob($setting));
            }
        });
    }
}
