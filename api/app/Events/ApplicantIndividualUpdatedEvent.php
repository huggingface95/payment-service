<?php

namespace App\Events;

use App\Models\ApplicantIndividual;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicantIndividualUpdatedEvent extends Event
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
