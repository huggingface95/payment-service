<?php

namespace App\Listeners\Log;

use App\Events\Applicant\ApplicantIndividualNoteCreatedEvent;
use App\Exceptions\GraphqlException;
use App\Models\Members;
use App\Services\KycTimelineService;

class LogApplicantIndividualNoteChangesListener
{
    public function __construct(
        protected KycTimelineService $kycTimelineService
    ) {
    }

    /**
     * @throws GraphqlException
     */
    public function handle(ApplicantIndividualNoteCreatedEvent $event): void
    {
        $note = $event->applicantIndividualNote;
        $member = null;
        if (auth()->user() instanceof Members) {
            $member = auth()->user();
        }

        $this->kycTimelineService->logApplicantIndividualNote($note, $member);
    }
}
