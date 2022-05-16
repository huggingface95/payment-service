<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\BaseModel;
use App\Models\GroupRole;
use App\Models\Groups;
use App\Models\Members;

class GroupMutator extends BaseMutator
{
    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function create($root, array $args)
    {

        if ($args['group_type_id'][0] == 1 && isset($args['payment_provider_id'])) {
            throw new GraphqlException('Payment Provider is not be used for this group',"internal", 500);
        }
        if ($args['group_type_id'][0] == 1 && isset($args['commission_template_id'])) {
            throw new GraphqlException('Commission Template is not be used for this group',"internal", 500);
        }
        if(!isset($args['company_id'])) {
            $member = Members::find(BaseModel::DEFAULT_MEMBER_ID);
            $args['company_id'] = $member->company_id;
        }
        $group = Groups::find($args['group_type_id']);
        if (!$group) {
            throw new GraphqlException('An entry with this group does not exist',"not found",404);
        }
        return GroupRole::create($args);
    }

    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        if ($args['group_type_id'][0] == 1 && isset($args['payment_provider_id'])) {
            throw new GraphqlException('Payment Provider is not be used for this group',"internal", 500);
        }
        if ($args['group_type_id'][0] == 1 && isset($args['commission_template_id'])) {
            throw new GraphqlException('Commission Template is not be used for this group',"internal", 500);
        }
        if(!isset($args['company_id'])) {
            $member = Members::find(BaseModel::DEFAULT_MEMBER_ID);
            $args['company_id'] = $member->company_id;
        }
        $group = Groups::find($args['group_type_id']);
        if (!$group) {
            throw new GraphqlException('An entry with this group does not exist',"not found",404);
        }
        $groupRole = GroupRole::find($args['id']);
        if (!$groupRole) {
            throw new GraphqlException('An entry with this id does not exist',"not found",404);
        }

        $groupRole->update($args);
        return $groupRole;
    }

    /**
     * Delete department
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function delete($root, array $args)
    {
        try {
            $group = GroupRole::find($args['id']);
            $group->delete();
            return $group;

        } catch (\Exception $exception)
        {
            throw new GraphqlException('Group are already in use by member',"use");
        }

    }

    public function setMemberGroup($root, array $args)
    {
        $groupRole = GroupRole::create($args);
        $member = Members::where('id', '=', Members::DEFAULT_MEMBER_ID)->first();
        $role_id = $groupRole->id;
        if ($role_id) {
            $member->groupRoles()->sync([$role_id], true);
        }

        return $groupRole;
    }



}
