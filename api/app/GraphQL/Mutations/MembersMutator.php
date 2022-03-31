<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\DepartmentPosition;
use App\Models\GroupRole;
use App\Models\Members;
use GraphQL\Exception\InvalidArgument;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 *
 */
class MembersMutator extends BaseMutator
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
            $password =$args['password'];
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

        if (isset($args['additional_fields'])) {
            $args['additional_fields']  = $this->setAdditionalField($args['additional_fields']);
        }
        if (isset($args['additional_info_fields'])) {
            $args['additional_info_fields']  = $this->setAdditionalField($args['additional_info_fields']);
        }

        if(isset($args['department_position']))
        {
            $departamentPosition = DepartmentPosition::find($args['department_position']);

            if (!isset($departamentPosition)) {
                throw new GraphqlException('An entry with this id does not exist',"not found",404);
            }

            if ($departamentPosition->department->company->id !== $member->company_id) {
                throw new GraphqlException('Position is not this company',"internal",500);
            }

            $member->department_position_id = $args['department_position'];
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
        if ($args['role_id']) {
            $groupRole = $this->getMemberGroupRole($args['role_id']);
            $args['member_group_role_id'] = $groupRole->id;
        }

        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);
        return Members::create($args);
    }



    /**
     * @param int $roleId
     * @return mixed
     */
    private function getMemberGroupRole(int $roleId)
    {
        return GroupRole::where(['group_type_id'=>1, 'role_id' => $roleId])->first();
    }

}
