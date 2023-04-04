<?php

namespace App\Console\Commands;

use App\Jobs\ProcessLedgerDayHistoryJob;
use App\Models\CompanyLedgerSettings;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
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

        $chunkSize = 2;
        $timeNow = Carbon::now()->format('H:i:s');

        $this->info('Day of week: ' . Carbon::now()->dayOfWeekIso);
        $this->info('Day of month: ' . Carbon::now()->day);

        // $query = CompanyLedgerSettings::query()
        //     ->where(function (Builder $query) use ($timeNow) {
        //         $query->where('end_of_day_time', '<=', $timeNow)
        //             ->orWhere(function (Builder $query) use ($timeNow) {
        //                 $query->where('end_of_week_time', '<=', $timeNow)
        //                     ->where('end_of_week_day', Carbon::now()->dayOfWeekIso);
        //             })
        //             ->orWhere(function (Builder $query) use ($timeNow) {
        //                 $query->where('end_of_month_time', '<=', $timeNow)
        //                     ->where('end_of_month_day', Carbon::now()->day);
        //             });
        //     });

        $query = CompanyLedgerSettings::query()->where('end_of_day_time', '<=', $timeNow);
        
        $query->chunkById($chunkSize, function (Collection $settings) {
            $this->info('Chunk size: ' . $settings->count());
            $this->info($settings);

            foreach ($settings as $setting) {
                $this->info('Company: ' . $setting->company_id);

                dispatch(new ProcessLedgerDayHistoryJob($setting));
            }
        });

    }
}
