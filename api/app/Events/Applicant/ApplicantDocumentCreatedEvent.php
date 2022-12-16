<?php

namespace App\Events\Applicant;

use App\Events\Event;
use App\Models\ApplicantDocument;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicantDocumentCreatedEvent extends Event
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ApplicantDocument $file)
    {
    }
}
