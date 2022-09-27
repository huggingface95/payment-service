<?php

namespace App\Events;

use App\Models\Payments;

class PaymentCreatedEvent extends Event
{
    public $payment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Payments $payment)
    {
        $this->payment = $payment;
    }
}
