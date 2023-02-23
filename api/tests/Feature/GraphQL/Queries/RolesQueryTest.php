<?php

namespace Tests;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesQueryTest extends TestCase
{
    /**
     * Roles Query Testing
     *
     * @return void
     */
    public function testQueryRolesNoAuth(): void
    {
        $this->graphQL('
            {
                roles {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryRolesById(): void
    {
        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->where('id', '!=', Role::SUPER_ADMIN_ID)
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query Role($id: ID!) {
                    role(id: $id) {
                        id
                        name
                    }
                }',
                'variables' => [
                    'id' => (string) $role[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'role' => [
                    'id' => (string) $role[0]->id,
                    'name' => (string) $role[0]->name,
                ],
            ],
        ]);
    }

    public function testQueryRolesByGroupTypes(): void
    {
        $roles = DB::connection('pgsql_test')
            ->table('roles')
            ->where('group_type_id', 1)
            ->where('id', '!=', Role::SUPER_ADMIN_ID)
            ->get();

        foreach ($roles as $role) {
            $data[] = [
                'id' => (string) $role->id,
                'name' => (string) $role->name,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query {
                    roles(filter: { column: HAS_GROUP_TYPE_MIXED_ID_OR_NAME, value: 1 }) {
                        data {
                            id
                            name
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'roles' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryByCompanyId(): void
    {
        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query {
                    roles(filter: { column: COMPANY_ID, value: 1 }) {
                        data {
                            id
                            name
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $role->id,
            'name' => (string) $role->name,
        ]);
    }

    public function testQueryByRoleName(): void
    {
        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query Roles($name: Mixed) {
                    roles(filter: { column: NAME, operator: ILIKE, value: $name }) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'name' => (string) $role[0]->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'roles' => [
                    'data' => [[
                        'id' => (string) $role[0]->id,
                        'name' => (string) $role[0]->name,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryByGroupId(): void
    {
        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'ASC')
            ->first();

        $groupRole = DB::connection('pgsql_test')
            ->table('group_role')
            ->where('role_id', $role->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query Roles($id: Mixed) {
                    roles(filter: { column: HAS_GROUPS_MIXED_ID_OR_NAME, value: $id }) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $groupRole->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'roles' => [
                    'data' => [[
                        'id' => (string) $role->id,
                        'name' => (string) $role->name,
                    ]],
                ],
            ],
        ]);
    }
}
