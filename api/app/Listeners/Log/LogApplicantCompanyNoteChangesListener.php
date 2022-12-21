<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantCompanyNoteCreatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Members;
use App\Services\KycTimelineService;

class LogApplicantCompanyNoteChangesListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantCompanyNoteCreatedEvent $event): void
    {
        $note = $event->applicantCompanyNotes;
        $member = null;
        if (auth()->user() instanceof Members) {
            $member = auth()->user();
        }

        $this->kycTimelineService->logApplicantCompanyNote($note, $member);
    }
}
