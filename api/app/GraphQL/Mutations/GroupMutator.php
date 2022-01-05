<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\GroupRole;
use App\Models\Groups;

class GroupMutator extends BaseMutator
{
    /**
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function create($root, array $args)
    {

        if ($args['group_id'][0] == 1 && isset($args['payment_provider_id'])) {
            throw new GraphqlException('Payment Provider is not be used for this group',"internal", 500);
        }
        if ($args['group_id'][0] == 1 && isset($args['commission_template_id'])) {
            throw new GraphqlException('Commission Template is not be used for this group',"internal", 500);
        }
        $group = Groups::find($args['group_id']);
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
        if ($args['group_id'][0] == 1 && isset($args['payment_provider_id'])) {
            throw new GraphqlException('Payment Provider is not be used for this group',"internal", 500);
        }
        if ($args['group_id'][0] == 1 && isset($args['commission_template_id'])) {
            throw new GraphqlException('Commission Template is not be used for this group',"internal", 500);
        }
        $group = Groups::find($args['group_id']);
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



}
