<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantIndividualSentEmailRegistrationDetailsEvent;
use App\Exceptions\GraphqlException;
use App\Services\KycTimelineService;

class LogApplicantIndividualSentEmailRegistrationDetailsListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantIndividualSentEmailRegistrationDetailsEvent $event): void
    {
        $applicantIndividual = $event->applicantIndividual;

        $this->kycTimelineService->logApplicantSentEmailRegistrationDetails($applicantIndividual);
    }
}
