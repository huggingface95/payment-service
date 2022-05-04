<?php

namespace App\Policies;

class ApplicantIndividualPolicy extends BasePolicy
{

    public function matched_users(): bool
    {
        //Custom logic
        return true;
    }

}
