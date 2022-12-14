<?php

namespace App\Listeners\Log;

use App\Events\ApplicantDocumentUpdatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Members;
use App\Services\KycTimelineService;

class LogApplicantDocumentUpdatedListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantDocumentUpdatedEvent $event): void
    {
        $file = $event->file;
        $member = null;
        if (auth()->user() instanceof Members) {
            $member = auth()->user();
        }

        $this->kycTimelineService->logApplicantDocumentUpdated($file, $member);
    }
}
