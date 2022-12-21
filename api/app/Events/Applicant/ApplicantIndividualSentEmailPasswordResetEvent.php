<?php

namespace App\Events\Applicant;

use App\Events\Event;
use App\Models\ApplicantIndividual;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicantIndividualSentEmailPasswordResetEvent extends Event
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ApplicantIndividual $applicantIndividual)
    {
    }
}
