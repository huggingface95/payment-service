<?php

namespace App\Events\Applicant;

use App\Events\Event;
use App\Models\ApplicantCompanyNotes;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicantCompanyNoteCreatedEvent extends Event
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ApplicantCompanyNotes $applicantCompanyNotes)
    {
    }
}
