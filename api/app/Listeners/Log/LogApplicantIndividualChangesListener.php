<?php

namespace App\Listeners\Log;

use App\Events\ApplicantIndividualUpdatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Members;
use App\Services\KycTimelineService;

class LogApplicantIndividualChangesListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantIndividualUpdatedEvent $event): void
    {
        $applicantIndividual = $event->applicantIndividual;
        $member = null;
        if (auth()->user() instanceof Members) {
            $member = auth()->user();
        }

        $this->kycTimelineService->logApplicantIndividual($applicantIndividual, $member);
    }
}
