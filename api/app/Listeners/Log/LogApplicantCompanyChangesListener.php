<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantCompanyUpdatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Members;
use App\Services\KycTimelineService;

class LogApplicantCompanyChangesListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantCompanyUpdatedEvent $event): void
    {
        $applicantCompany = $event->applicantCompany;
        $member = null;
        if (auth()->user() instanceof Members) {
            $member = auth()->user();
        }

        $this->kycTimelineService->logApplicantCompany($applicantCompany, $member);
    }
}
