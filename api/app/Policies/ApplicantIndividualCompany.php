<?php

namespace App\Policies;

class ApplicantIndividualCompany extends BasePolicy
{

    public function change_password(): bool
    {
        //Custom logic
        return true;
    }

}
