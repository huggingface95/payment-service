<?php

namespace App\Policies;

class ApplicantIndividualPolicy extends BasePolicy
{

    public function matched_users($user, $model): bool
    {
        //Custom logic
        return true;
    }

}
