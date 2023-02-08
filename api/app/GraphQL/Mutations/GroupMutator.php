<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\BaseModel;
use App\Models\GroupRole;
use App\Models\GroupRoleProvider;
use App\Models\GroupType;
use App\Models\Members;
use Illuminate\Support\Facades\DB;

class GroupMutator extends BaseMutator
{
    /**
     * @param $root
     * @param  array  $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        if ($args['group_type_id'][0] == 1 && isset($args['payment_provider_id'])) {
            throw new GraphqlException('Payment Provider is not be used for this group', 'internal', 500);
        }
        if ($args['group_type_id'][0] == 1 && isset($args['commission_template_id'])) {
            throw new GraphqlException('Commission Template is not be used for this group', 'internal', 500);
        }
        if (! isset($args['company_id'])) {
            $member = Members::find(BaseModel::DEFAULT_MEMBER_ID);
            $args['company_id'] = $member->company_id;
        }
        $group = GroupType::find($args['group_type_id']);
        if (! $group) {
            throw new GraphqlException('An entry with this group does not exist', 'not found', 404);
        }
        if (isset($args['role_id']) && trim($args['role_id']) == '') {
            $args['role_id'] = null;
        }

        $groupRole = GroupRole::create($args);
        if (! $groupRole) {
            throw new GraphqlException('Create group error', 'internal', 500);
        }

        if (isset($args['providers'])) {
            $this->setGroupRoleProviders($groupRole, $args);
        }

        return $groupRole;
    }

    /**
     * @param $root
     * @param  array  $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        if ($args['group_type_id'][0] == 1 && isset($args['payment_provider_id'])) {
            throw new GraphqlException('Payment Provider is not be used for this group', 'internal', 500);
        }
        if ($args['group_type_id'][0] == 1 && isset($args['commission_template_id'])) {
            throw new GraphqlException('Commission Template is not be used for this group', 'internal', 500);
        }
        if (! isset($args['company_id'])) {
            $member = Members::find(BaseModel::DEFAULT_MEMBER_ID);
            $args['company_id'] = $member->company_id;
        }
        $group = GroupType::find($args['group_type_id']);
        if (! $group) {
            throw new GraphqlException('An entry with this group does not exist', 'not found', 404);
        }
        $groupRole = GroupRole::find($args['id']);
        if (! $groupRole) {
            throw new GraphqlException('An entry with this id does not exist', 'not found', 404);
        }
        if (isset($args['role_id']) && trim($args['role_id']) == '') {
            $args['role_id'] = null;
        }

        $groupRole->update($args);

        if (isset($args['providers'])) {
            $this->setGroupRoleProviders($groupRole, $args);
        }

        return $groupRole;
    }

    /**
     * Delete department
     *
     * @param $root
     * @param  array  $args
     * @return mixed
     */
    public function delete($root, array $args)
    {
        $group = GroupRole::find($args['id']);
        $relation = DB::table('group_role_members_individuals')
                ->select('*')
                ->where('group_role_id', '=', $args['id'])->get();
        if ($relation != '[]') {
            throw new GraphqlException('It is not possible to delete the group because it has active users', 'use');
        }
        $group->delete();

        return $group;
    }

    public function setMemberGroup($root, array $args)
    {
        $groupRole = GroupRole::create($args);
        $member = Members::where('id', '=', Members::DEFAULT_MEMBER_ID)->first();
        $role_id = $groupRole->id;
        if ($role_id) {
            $member->groupRoles()->sync([$role_id], true);
        }

        if (isset($args['providers'])) {
            $this->setGroupRoleProviders($groupRole, $args);
        }

        return $groupRole;
    }

    private function setGroupRoleProviders(GroupRole $groupRole, array $args): void
    {
        $groupRole->groupRoleProviders()->delete();

        foreach ($args['providers'] as $provider) {
            GroupRoleProvider::insert([
                'group_role_id' => $groupRole->id,
                'payment_provider_id' => $provider['payment_provider_id'],
                'commission_template_id' => $provider['commission_template_id'],
                'is_default' => $provider['is_default'] ?? false,
            ]);
        }
    }
}
