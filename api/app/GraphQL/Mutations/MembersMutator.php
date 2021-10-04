<?php

namespace App\GraphQL\Mutations;

use App\Models\GroupRole;
use App\Models\Members;
use GraphQL\Exception\InvalidArgument;
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

        if (isset($args['additional_fields'])) {

            $fields = [];
            foreach ($args['additional_fields']  as $additionalField) {
                if (strlen($additionalField['field_value']) > config('app.max_length_string')) {
                    throw new InvalidArgument("Max length field is ". config('app.max_length_string'));
                }
                if ($additionalField['field_type'] === "Text" ) {
                    $additionalField['field_value'] = filter_var($additionalField['field_value'],FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
                }
                if ($additionalField['field_type'] === "TextArea"){
                    $additionalField['field_value'] = filter_var($additionalField['field_value'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }
                $fields[] = $additionalField;
            }
            $args['additional_fields'] = $fields;
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

    public function setMemberPosition($_, array $args)
    {
        if(isset($args['member_id']) && isset($args['department_position']))
        {
            $member = Members::where(['id'=>$args['member_id']])->first();
            $member->department_position_id = $args['department_position'];
            $member->update();
            return $member;
        }
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
