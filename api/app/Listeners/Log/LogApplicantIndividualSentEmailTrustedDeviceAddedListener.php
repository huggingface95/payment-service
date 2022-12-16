<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantIndividualSentEmailTrustedDeviceAddedEvent;
use App\Exceptions\GraphqlException;
use App\Services\KycTimelineService;

class LogApplicantIndividualSentEmailTrustedDeviceAddedListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantIndividualSentEmailTrustedDeviceAddedEvent $event): void
    {
        $applicantIndividual = $event->applicantIndividual;

        $this->kycTimelineService->logApplicantSentEmailTrustedDeviceAdded($applicantIndividual);
    }
}
