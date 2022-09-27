<?php

namespace App\Providers;

use App\Events\AccountCreatedEvent;
use App\Events\AccountUpdatedEvent;
use App\Events\PaymentCreatedEvent;
use App\Events\PaymentUpdatedEvent;
use App\Listeners\AccountCreatedListener;
use App\Listeners\AccountUpdatedListener;
use App\Listeners\PaymentCreatedListener;
use App\Listeners\PaymentUpdatedListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AccountUpdatedEvent::class => [
            AccountUpdatedListener::class,
        ],
        AccountCreatedEvent::class => [
            AccountCreatedListener::class,
        ],
        PaymentCreatedEvent::class => [
            PaymentCreatedListener::class,
        ],
        PaymentUpdatedEvent::class => [
            PaymentUpdatedListener::class,
        ],
    ];
}
