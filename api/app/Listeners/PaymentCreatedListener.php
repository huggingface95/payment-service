<?php

namespace App\Listeners;

use App\Enums\PaymentStatusEnum;
use App\Events\PaymentCreatedEvent;
use App\Services\AccountService;

class PaymentCreatedListener
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
     * @param  \App\Events\PaymentCreatedEvent  $event
     * @return void
     */
    public function handle(PaymentCreatedEvent $event)
    {
        $payment = $event->payment;

        if ($payment->status_id === PaymentStatusEnum::PENDING->value) {
            $this->accountService->setAmmountReserveOnBalance($payment);
        }
    }

}
