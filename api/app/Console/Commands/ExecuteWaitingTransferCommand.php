<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatusEnum;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExecuteWaitingTransferCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:execute-waiting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute waiting transfers';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected TransferOutgoingRepositoryInterface $transferRepository,
        protected TransferOutgoingService $transferService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $waitingTransfers = $this->transferRepository->getWaitingExecutionDateTransfers();

        if (!$waitingTransfers->isEmpty()) {
            foreach ($waitingTransfers as $transfer) {
                try {
                    DB::beginTransaction();

                    $this->info('Transfer id: ' . $transfer->id . ' is processing...');

                    $this->transferService->updateTransferStatus($transfer, [
                        'status_id' => PaymentStatusEnum::PENDING->value,
                    ]);

                    $this->transferService->updateTransferStatus($transfer, [
                        'status_id' => PaymentStatusEnum::SENT->value,
                    ]);

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();

                    $this->transferRepository->update($transfer, [
                        'status_id' => PaymentStatusEnum::ERROR->value,
                        'system_message' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
