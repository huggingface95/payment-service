<?php

namespace App\Events;

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
