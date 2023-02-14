<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class GroupsQueryTest extends TestCase
{
    /**
     * Groups Query Testing
     *
     * @return void
     */
    public function testQueryGroupListNoAuth(): void
    {
        $this->graphQL('
            {
                groupList {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryGroupType(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_types')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroupType($id: ID) {
                    group_type(id: $id) {
                        id
                        name
                    }
                }',
                'variables' => [
                    'id' => (string) $group->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'group_type' => [
                    'id' => (string) $group->id,
                    'name' => (string) $group->name,
                ],
            ],
        ]);
    }

    public function testQueryGroupListById(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroupType($id: Mixed) {
                    groupList(filter: { column: ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $group->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'groupList' => [
                    'data' => [[
                        'id' => (string) $group->id,
                        'name' => (string) $group->name,
                        'description' => (string) $group->description,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryGroupListByCompanyId(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroupByCompanyId($id: Mixed) {
                    groupList(filter: { column: COMPANY_ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $group->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $group->id,
            'name' => (string) $group->name,
            'description' => (string) $group->description,
        ]);
    }

    public function testQueryGroupListByRoleId(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($id: Mixed) {
                    groupList(filter: { column: ROLE_ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $group->role_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'groupList' => [
                    'data' => [[
                        'id' => (string) $group->id,
                        'name' => (string) $group->name,
                        'description' => (string) $group->description,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryGroupListByName(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($name: Mixed) {
                    groupList(filter: { column: NAME, operator: LIKE, value: $name }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'name' => (string) $group->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'groupList' => [
                    'data' => [[
                        'id' => (string) $group->id,
                        'name' => (string) $group->name,
                        'description' => (string) $group->description,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryGroupListByGroupTypeId(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($group_type: Mixed) {
                    groupList(filter: { column: GROUP_TYPE_ID, value: $group_type }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'group_type' => (string) $group->group_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $group->id,
            'name' => (string) $group->name,
            'description' => (string) $group->description,
        ]);
    }

    public function testQueryGroupListByIsActive(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    groupList(filter: { column: IS_ACTIVE, value: false }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }'
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $group->id,
            'name' => (string) $group->name,
            'description' => (string) $group->description,
        ]);
    }

    public function testQueryGroupListByModuleId(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($module_id: Mixed) {
                    groupList(filter: { column: MODULE_ID, value: $module_id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'module_id' => (string) $group->module_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $group->id,
            'name' => (string) $group->name,
            'description' => (string) $group->description,

        ]);
    }

    public function testQueryGroupListByCommissionTemplateId(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($commission_template_id: Mixed) {
                    groupList(filter: { column: COMMISSION_TEMPLATE_ID, value: $commission_template_id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'commission_template_id' => (string) $group->commission_template_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $group->id,
            'name' => (string) $group->name,
            'description' => (string) $group->description,
        ]);
    }

    public function testQueryGroupListByPaymentProviderId(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role_view')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($payment_provider_id: Mixed) {
                    groupList(filter: { column: COMMISSION_TEMPLATE_ID, value: $payment_provider_id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'payment_provider_id' => (string) $group->payment_provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $group->id,
            'name' => (string) $group->name,
            'description' => (string) $group->description,
        ]);
    }

    public function testQueryGroupTypeHasGroupsByCompanyId(): void
    {
        $groupType = DB::connection('pgsql_test')
            ->table('group_types')
            ->first();

        $groupRole = DB::connection('pgsql_test')
            ->table('group_role')
            ->where('group_type_id', $groupType->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($company_id: Mixed) {
                    group_types(filter: { column: HAS_GROUPS_FILTER_BY_COMPANY_ID, value: $company_id }) {
                            id
                            name
                    }
                }',
                'variables' => [
                    'company_id' => (string) $groupRole->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groupType->id,
            'name' => (string) $groupType->name,
        ]);
    }

    public function testQueryGroupTypeHasGroupsByRoleId(): void
    {
        $groupType = DB::connection('pgsql_test')
            ->table('group_types')
            ->first();

        $groupRole = DB::connection('pgsql_test')
            ->table('group_role')
            ->where('group_type_id', $groupType->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($role_id: Mixed) {
                    group_types(filter: { column: HAS_GROUPS_FILTER_BY_ROLE_ID, value: $role_id }) {
                            id
                            name
                    }
                }',
                'variables' => [
                    'role_id' => (string) $groupRole->role_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groupType->id,
            'name' => (string) $groupType->name,
        ]);
    }

    public function testQueryGroupTypeByRoleId(): void
    {
        $groupType = DB::connection('pgsql_test')
            ->table('group_types')
            ->first();

        $groupRole = DB::connection('pgsql_test')
            ->table('roles')
            ->where('group_type_id', $groupType->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($role_id: Mixed) {
                    group_types(filter: { column: HAS_ROLES_FILTER_BY_ID, value: $role_id }) {
                            id
                            name
                    }
                }',
                'variables' => [
                    'role_id' => (string) $groupRole->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groupType->id,
            'name' => (string) $groupType->name,
        ]);
    }

    public function testQueryGroupTypesList(): void
    {
        $groupType = DB::connection('pgsql_test')
            ->table('group_types')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    groupTypeList {
                        data {
                            id
                            name
                        }
                    }
                }'
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groupType->id,
            'name' => (string) $groupType->name,
        ]);
    }

    public function testQueryGroupsById(): void
    {
        $groups = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($id: Mixed) {
                    groups(filter: { column: ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $groups->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groups->id,
            'name' => (string) $groups->name,
            'description' => (string) $groups->description,
        ]);
    }

    public function testQueryGroupsByCompanyId(): void
    {
        $groups = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($id: Mixed) {
                    groups(filter: { column: COMPANY_ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $groups->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groups->id,
            'name' => (string) $groups->name,
            'description' => (string) $groups->description,
        ]);
    }

    public function testQueryGroupsByRoleId(): void
    {
        $groups = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($id: Mixed) {
                    groups(filter: { column: ROLE_ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $groups->role_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groups->id,
            'name' => (string) $groups->name,
            'description' => (string) $groups->description,
        ]);
    }

    public function testQueryGroupsByName(): void
    {
        $groups = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($name: Mixed) {
                    groups(filter: { column: NAME, operator: ILIKE value: $name }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'name' => (string) $groups->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groups->id,
            'name' => (string) $groups->name,
            'description' => (string) $groups->description,
        ]);
    }

    public function testQueryGroupsByGroupTypeId(): void
    {
        $groups = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query GetGroup($id: Mixed) {
                    groups(filter: { column: GROUP_TYPE_ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $groups->group_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $groups->id,
            'name' => (string) $groups->name,
            'description' => (string) $groups->description,
        ]);
    }
}
