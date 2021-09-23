<?php

namespace App\GraphQL\Mutations;

use App\Models\GroupRole;
use App\Models\Members;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class MembersMutator
{


    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function create($_, array $args)
    {
        if (!isset($args['password'])) {
            $password = Str::random(8);
        } else {
            $password =$args['password_hash'];
        }

        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);
        $groupRole = $this->getMemberGroupRole($args['role_id']);

        if ($groupRole) {
            $args['member_group_role_id'] = $groupRole->id;
            return Members::create($args);
        }

        return  false;
    }

    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function update($_, array $args)
    {
        $member = Members::find($args['id']);
        if ($args['role_id']) {
            $groupRole = $this->getMemberGroupRole($args['role_id']);
            $args['member_group_role_id'] = $groupRole->id;
        }

        $member->update($args);

        return $member;
    }

    /**
     * @param $_
     * @param array $args
     * @return mixed
     */
    public function invite($_, array $args)
    {
        $password = Str::random(8);
        $args['is_active'] = false;

        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);
        return Members::create($args);
    }

    /**
     * @param $_
     * @param array $args
     * @return array
     */
    public function setPassword($_, array $args)
    {

        $args['password_hash'] = Hash::make($args['password']);
        $args['password_salt'] = Hash::make($args['password']);
        return $args;
    }


    /**
     * @param int $roleId
     * @return mixed
     */
    private function getMemberGroupRole(int $roleId)
    {
        return GroupRole::where(['group_id'=>1, 'role_id' => $roleId])->first();
    }

}
