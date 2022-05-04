<?php

namespace App\Policies;

class GroupRolePolicy extends BasePolicy
{

    public function setMemberGroup(): bool
    {
        //Custom logic
        return true;
    }

}
