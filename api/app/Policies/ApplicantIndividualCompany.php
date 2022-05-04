<?php

namespace App\Policies;

class ApplicantIndividualCompany extends BasePolicy
{

    public function change_password($user, $model): bool
    {
        //Custom logic
        return true;
    }

}
