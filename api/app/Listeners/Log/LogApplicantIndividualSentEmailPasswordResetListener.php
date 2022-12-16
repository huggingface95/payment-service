<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantIndividualSentEmailPasswordResetEvent;
use App\Exceptions\GraphqlException;
use App\Services\KycTimelineService;

class LogApplicantIndividualSentEmailPasswordResetListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantIndividualSentEmailPasswordResetEvent $event): void
    {
        $applicantIndividual = $event->applicantIndividual;

        $this->kycTimelineService->logApplicantSentEmailPasswordReset($applicantIndividual);
    }
}
