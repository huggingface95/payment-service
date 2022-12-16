<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantIndividualSentEmailTrustedDeviceRemovedEvent;
use App\Exceptions\GraphqlException;
use App\Services\KycTimelineService;

class LogApplicantIndividualSentEmailTrustedDeviceRemovedListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantIndividualSentEmailTrustedDeviceRemovedEvent $event): void
    {
        $applicantIndividual = $event->applicantIndividual;

        $this->kycTimelineService->logApplicantSentEmailTrustedDeviceRemoved($applicantIndividual);
    }
}
