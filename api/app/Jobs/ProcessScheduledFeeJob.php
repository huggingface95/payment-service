<?php

namespace App\Jobs;

use App\Models\PriceListFeeScheduled;
use App\Models\PriceListFeeScheduledTask;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\PriceListFeeScheduledRepositoryInterface;
use App\Services\PriceListFeeScheduledService;
use App\Services\TransferOutgoingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class ProcessScheduledFeeJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Collection $tasks,
        protected PriceListFeeScheduled $scheduledFee
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        TransferOutgoingService $transferService,
        PriceListFeeScheduledService $feeScheduledService,
        AccountRepository $taskRepository,
        PriceListFeeScheduledRepositoryInterface $feeScheduledRepository,
    ) {
        info("\n\n>> START ".count($this->tasks).' tasks for '.$this->scheduledFee->id);

        foreach ($this->tasks as $task) {
            info('Account:       '.$task->account_id);

            $dates = $feeScheduledService->getFromToDates($this->scheduledFee); //, $task ?
            if (empty($dates)) {
                info("Skept by date\n");
                $this->deleteTask($task);

                continue;
            }

            info('Date from:     '.$dates['dateFrom']);
            info('Date to:       '.$dates['dateTo']);

            // Get amount of cash flow for period
            $amount = $taskRepository->getAmountOfCashFlowForPeriodByAccountId(
                $task->account_id,
                $dates['dateFrom'],
                $dates['dateTo']
            );

            info('Amount:        '.$amount);
            info('Currency:      '.$task->currency_id);
            if ($amount == 0) {
                $this->deleteTask($task);

                continue;
            }

            // Get fee amount
            $feeAmount = $transferService->commissionCalculationFeeScheduled(
                $amount,
                $this->scheduledFee->priceListFee,
                $task->currency_id
            );

            info('Amount fee:    '.$feeAmount);
            if ($feeAmount == 0) {
                $this->deleteTask($task);

                continue;
            }

            // Create and sent fee transfer
            $transfer = $transferService->createScheduledFeeTransfer([
                'amount' => $feeAmount,
                'account_id' => $task->account_id,
                'currency_id' => $task->currency_id,
                'reason' => $this->scheduledFee->priceListFee->name,
            ]);

            if ($transfer) {
                $this->deleteTask($task);

                info("Transfer fee created\n");
            }

            $transferService->updateTransferFeeStatusToSent($transfer);
        }

        info('');
        info('Check num tasks');

        $dateToday = Carbon::today()->format('Y-m-d');
        if (PriceListFeeScheduledTask::where('price_list_fee_scheduled_id', $this->scheduledFee->id)->where('date', $dateToday)->count() == 0) {
            $feeScheduledRepository->update(
                $this->scheduledFee,
                ['executed_date' => $dateToday]
            );

            info('Updated date executed');
        }

        info("END\n\n");
    }

    private function deleteTask($task): void
    {
        info('Delete task');

        PriceListFeeScheduledTask::where([
            'price_list_fee_scheduled_id' => $task->price_list_fee_scheduled_id,
            'account_id' => $task->account_id,
            'currency_id' => $task->currency_id,
            'date' => $task->date,
        ])->delete();
    }
}
