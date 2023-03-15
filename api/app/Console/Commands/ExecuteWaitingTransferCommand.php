<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatusEnum;
use App\Repositories\Interfaces\TransferOutgoingRepositoryInterface;
use App\Services\TransferOutgoingService;
use Illuminate\Console\Command;

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

        if ($waitingTransfers) {
            foreach ($waitingTransfers as $transfer) {
                try {
                    $this->transferService->updateTransferStatus($transfer, [
                        'status_id' => PaymentStatusEnum::PENDING->value,
                    ]);

                    $this->transferService->updateTransferStatus($transfer, [
                        'status_id' => PaymentStatusEnum::SENT->value,
                    ]);
                } catch (\Exception $e) {
                    $this->transferRepository->update($transfer, [
                        'status_id' => PaymentStatusEnum::ERROR->value,
                        'system_message' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
