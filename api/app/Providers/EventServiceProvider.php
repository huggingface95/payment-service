<?php

namespace App\Providers;

use App\Events\AccountUpdatedEvent;
use App\Events\ApplicantDocumentCreatedEvent;
use App\Events\ApplicantDocumentUpdatedEvent;
use App\Events\ApplicantIndividualNoteCreatedEvent;
use App\Events\ApplicantIndividualSentEmailVerificationEvent;
use App\Events\ApplicantIndividualUpdatedEvent;
use App\Events\PaymentCreatedEvent;
use App\Events\PaymentUpdatedEvent;
use App\Listeners\AccountUpdatedListener;
use App\Listeners\Log\LogApplicantDocumentCreatedListener;
use App\Listeners\Log\LogApplicantDocumentUpdatedListener;
use App\Listeners\Log\LogApplicantIndividualChangesListener;
use App\Listeners\Log\LogApplicantIndividualNoteChangesListener;
use App\Listeners\Log\LogApplicantIndividualSentEmailVerificationListener;
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
        ApplicantIndividualUpdatedEvent::class => [
            LogApplicantIndividualChangesListener::class,
        ],
        ApplicantIndividualNoteCreatedEvent::class => [
            LogApplicantIndividualNoteChangesListener::class,
        ],
        ApplicantDocumentCreatedEvent::class => [
            LogApplicantDocumentCreatedListener::class,
        ],
        ApplicantDocumentUpdatedEvent::class => [
            LogApplicantDocumentUpdatedListener::class,
        ],
        ApplicantIndividualSentEmailVerificationEvent::class => [
            LogApplicantIndividualSentEmailVerificationListener::class
        ],
        PaymentCreatedEvent::class => [
            PaymentCreatedListener::class,
        ],
        PaymentUpdatedEvent::class => [
            PaymentUpdatedListener::class,
        ],
    ];
}
