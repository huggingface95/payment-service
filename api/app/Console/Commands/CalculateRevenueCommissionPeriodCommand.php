<?php

namespace App\Console\Commands;

use App\Enums\PeriodEnum;
use App\Jobs\ProcessLedgerPeriodHistoryJob;
use App\Models\CompanyLedgerSettings;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CalculateRevenueCommissionPeriodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:calculate-revenue-period 
                            {period : Week or Month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Revenue commission for companies by period (Week or Month)';

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
        $dayOfWeek = Carbon::now()->dayOfWeekIso;

        $this->info('Chunk size: ' . $chunkSize);
        $this->info('Period: ' . $this->argument('period'));
        $this->info('Time now: ' . $timeNow);

        $query = CompanyLedgerSettings::query();
        if ($this->argument('period') == PeriodEnum::WEEK->value) {
            $this->info('Day of week: ' . $dayOfWeek);

            $query->where('end_of_week_day', $dayOfWeek)
                ->where('end_of_week_time', '<=', $timeNow);
        } elseif ($this->argument('period') == PeriodEnum::MONTH->value) {
            $this->info('Day of month: ' . Carbon::now()->day);

            $query->where('end_of_month_day', Carbon::now()->day)
                ->where('end_of_month_time', '<=', $timeNow);
        } else {
            $this->error('Period is not valid');
            exit;
        }

        $query->chunkById($chunkSize, function (Collection $settings) {
            foreach ($settings as $setting) {
                $this->info('Processing for company: ' . $setting->company_id);

                dispatch(new ProcessLedgerPeriodHistoryJob($setting, $this->argument('period')));
            }
        });
    }
}
