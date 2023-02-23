<?php

namespace App\Console\Commands;

use App\Jobs\ProcessScheduledFeeJob;
use App\Models\PriceListFeeScheduledTask;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\PriceListFeeScheduledRepositoryInterface;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\PriceListFeeScheduledService;
use App\Services\TransferOutgoingService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExecuteFeeScheduledTransferCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:execute-fee-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute scheduled fee transfers';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected TransferOutgoingRepositoryInterface $transferRepository,
        protected TransferOutgoingService $transferService,
        protected PriceListFeeScheduledService $priceListFeeScheduledService,
        protected AccountRepository $accountRepository,
        protected PriceListFeeScheduledRepositoryInterface $priceListFeeScheduledRepository,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get scheduled fees
        $chunkSize = 100;
        $dateToday = Carbon::now()->format('Y-m-d');
        $scheduledFees = $this->priceListFeeScheduledRepository->getScheduledFeesByDate($dateToday);

        foreach ($scheduledFees as $scheduledFee) {
            $this->info('Scheduled fee: '.$scheduledFee->priceListFee->name);

            // Store scheduled tasks
            $this->priceListFeeScheduledService->storeSchdeluedTasksForTodayById($scheduledFee->id);

            // Get scheduled tasks by chunks and run the job
            PriceListFeeScheduledTask::where('price_list_fee_scheduled_id', $scheduledFee->id)
                ->where('date', $dateToday)
                ->chunkById($chunkSize, function ($accounts) use ($scheduledFee) {
                    dispatch(new ProcessScheduledFeeJob($accounts, $scheduledFee));
                }
            );
        }
    }
}
