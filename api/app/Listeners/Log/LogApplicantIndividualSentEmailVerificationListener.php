<?php

namespace App\Listeners\Log;

use App\Events\ApplicantIndividualSentEmailVerificationEvent;
use App\Exceptions\GraphqlException;
use App\Services\KycTimelineService;

class LogApplicantIndividualSentEmailVerificationListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantIndividualSentEmailVerificationEvent $event): void
    {
        $applicantIndividual = $event->applicantIndividual;
        
        $this->kycTimelineService->logApplicantSentEmailVerification($applicantIndividual);
    }
}
