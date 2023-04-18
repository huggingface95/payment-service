<?php

namespace App\Providers;

use App\Events\Applicant\ApplicantCompanyNoteCreatedEvent;
use App\Events\Applicant\ApplicantCompanyUpdatedEvent;
use App\Events\Applicant\ApplicantDocumentCreatedEvent;
use App\Events\Applicant\ApplicantDocumentUpdatedEvent;
use App\Events\Applicant\ApplicantIndividualNoteCreatedEvent;
use App\Events\Applicant\ApplicantIndividualSentEmailPasswordResetEvent;
use App\Events\Applicant\ApplicantIndividualSentEmailRegistrationDetailsEvent;
use App\Events\Applicant\ApplicantIndividualSentEmailTrustedDeviceAddedEvent;
use App\Events\Applicant\ApplicantIndividualSentEmailTrustedDeviceRemovedEvent;
use App\Events\Applicant\ApplicantIndividualSentEmailVerificationEvent;
use App\Events\Applicant\ApplicantIndividualUpdatedEvent;
use App\Events\PaymentCreatedEvent;
use App\Events\PaymentUpdatedEvent;
use App\Listeners\Log\LogApplicantCompanyChangesListener;
use App\Listeners\Log\LogApplicantCompanyNoteChangesListener;
use App\Listeners\Log\LogApplicantDocumentCreatedListener;
use App\Listeners\Log\LogApplicantDocumentUpdatedListener;
use App\Listeners\Log\LogApplicantIndividualChangesListener;
use App\Listeners\Log\LogApplicantIndividualNoteChangesListener;
use App\Listeners\Log\LogApplicantIndividualSentEmailPasswordResetListener;
use App\Listeners\Log\LogApplicantIndividualSentEmailRegistrationDetailsListener;
use App\Listeners\Log\LogApplicantIndividualSentEmailTrustedDeviceAddedListener;
use App\Listeners\Log\LogApplicantIndividualSentEmailTrustedDeviceRemovedListener;
use App\Listeners\Log\LogApplicantIndividualSentEmailVerificationListener;
use App\Listeners\PaymentCreatedListener;
use App\Listeners\PaymentUpdatedListener;
use App\Models\Account;
use App\Models\TransferOutgoing;
use App\Observers\AccountObserver;
use App\Observers\BaseObserver;
use App\Observers\TransferOutgoingObserver;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ApplicantCompanyUpdatedEvent::class => [
            LogApplicantCompanyChangesListener::class,
        ],
        ApplicantCompanyNoteCreatedEvent::class => [
            LogApplicantCompanyNoteChangesListener::class,
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
        ApplicantIndividualSentEmailPasswordResetEvent::class => [
            LogApplicantIndividualSentEmailPasswordResetListener::class,
        ],
        ApplicantIndividualSentEmailRegistrationDetailsEvent::class => [
            LogApplicantIndividualSentEmailRegistrationDetailsListener::class,
        ],
        ApplicantIndividualSentEmailTrustedDeviceAddedEvent::class => [
            LogApplicantIndividualSentEmailTrustedDeviceAddedListener::class,
        ],
        ApplicantIndividualSentEmailTrustedDeviceRemovedEvent::class => [
            LogApplicantIndividualSentEmailTrustedDeviceRemovedListener::class,
        ],
        ApplicantIndividualSentEmailVerificationEvent::class => [
            LogApplicantIndividualSentEmailVerificationListener::class,
        ],
        PaymentCreatedEvent::class => [
            PaymentCreatedListener::class,
        ],
        PaymentUpdatedEvent::class => [
            PaymentUpdatedListener::class,
        ],
    ];

    public function boot(): void
    {
        Account::observe(AccountObserver::class);
        TransferOutgoing::observe(TransferOutgoingObserver::class);

        foreach (getAllModels() as $model) {
            $model::observe(BaseObserver::class);
        }

    }
}
