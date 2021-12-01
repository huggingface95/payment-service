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



}
