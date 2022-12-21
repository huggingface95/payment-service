<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantDocumentCreatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Members;
use App\Services\KycTimelineService;

class LogApplicantDocumentCreatedListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantDocumentCreatedEvent $event): void
    {
        $file = $event->file;
        $member = null;
        if (auth()->user() instanceof Members) {
            $member = auth()->user();
        }

        $this->kycTimelineService->logApplicantDocumentCreated($file, $member);
    }
}
