<?php

namespace App\Policies;

class MemberPolicy extends BasePolicy
{
    public function setMemberPassword(): bool
    {
        //Custom logic
        return true;
    }
}
