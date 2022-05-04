<?php

namespace App\Policies;

class GroupRolePolicy extends BasePolicy
{

    public function setMemberGroup($user, $model): bool
    {
        //Custom logic
        return true;
    }

}
