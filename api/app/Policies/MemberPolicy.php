<?php

namespace App\Policies;

class MemberPolicy extends BasePolicy
{
    public function setMemberPassword($user, $model): bool
    {
        //Custom logic
        return true;
    }
}
