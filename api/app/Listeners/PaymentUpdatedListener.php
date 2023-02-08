<?php

namespace App\Listeners;

use App\Enums\PaymentStatusEnum;
use App\Events\PaymentUpdatedEvent;
use App\Services\AccountService;

class PaymentUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(protected AccountService $accountService)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PaymentUpdatedEvent  $event
     * @return void
     */
    public function handle(PaymentUpdatedEvent $event)
    {
        $payment = $event->payment;

        if ($payment->status_id === PaymentStatusEnum::SENT->value) {
            $this->accountService->withdrawFromBalance($payment);
        }

        if ($payment->status_id === PaymentStatusEnum::CANCELED->value ||
            $payment->status_id === PaymentStatusEnum::ERROR->value) {
            $this->accountService->unsetAmmountReserveOnBalance($payment);
        }
    }
}
