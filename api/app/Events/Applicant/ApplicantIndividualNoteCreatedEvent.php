<?php

namespace App\Events\Applicant;

use App\Events\Event;
use App\Models\ApplicantIndividualNotes;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicantIndividualNoteCreatedEvent extends Event
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ApplicantIndividualNotes $applicantIndividualNote)
    {
    }
}
