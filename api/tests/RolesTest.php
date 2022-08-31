<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

class RolesTest extends TestCase
{
    /**
     * Roles Testing
     *
     * @return void
     */

    public function testCreateRole(): void
    {
        $this->login();

        $seq = DB::table('roles')->max('id') + 1;
        DB::select('ALTER SEQUENCE roles_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateRole(
                $name: String!
                $group_type_id: ID
                $description: String
                $company_id: ID
            ) {
            createRole(
                name: $name
                group_type_id: $group_type_id
                description: $description
                company_id: $company_id
            ) {
                id
            }
        }
        ', [
            'name' => 'Test Role Test',
            'group_type_id' => 1,
            'description' => 'Test Role Desc',
            'company_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJsonContains([
            [
                'id' => $id['data']['createRole']['id'],
            ],
        ]);
    }

    public function testUpdateRole(): void
    {
        $this->login();

        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
            mutation UpdateRole(
                $id: ID!
                $name: String!
            ) {
            updateRole(
                id: $id
                name: $name
            ) {
                id
            }
            }
        ', [
            'id' => strval($role[0]->id),
            'name' => 'Test updated role',
        ]);
        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateRole' => [
                    'id' => $id['data']['updateRole']['id'],
                ],
            ],
        ]);
    }

    public function testQueryRolesById(): void
    {
        $this->login();

        $role= DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        ($this->graphQL('
            query Role($id: ID!) {
                role(id: $id) {
                    id
                    name
                }
            }
        ', [
            'id' => strval($role[0]->id),
        ]))->seeJson([
            'data' => [
                'role' => [
                    'id' => strval($role[0]->id),
                    'name' => strval($role[0]->name),
                ],
            ],
        ]);
    }

    public function testQueryRolesByGroupTypes(): void
    {
        $this->login();

        $role= DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        ($this->graphQL('
            query {
                roles(filter: { column: HAS_GROUP_TYPE_MIXED_ID_OR_NAME, value: 1 }) {
                    data {
                        id
                        name
                    }
                }
            }
        '))->seeJson([
            'data' => [
                'roles' => [
                    'data' => [[
                        'id' => strval($role[0]->id),
                        'name' => strval($role[0]->name),
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryByCompanyId(): void
    {
        $this->login();

        $role= DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        ($this->graphQL('
            query {
                roles(filter: { column: COMPANY_ID, value: 1 }) {
                    data {
                        id
                        name
                    }
                }
            }
        '))->seeJsonContains([
           'id' => strval($role[0]->id),
           'name' => strval($role[0]->name),
        ]);
    }

    public function testQueryByRoleName(): void
    {
        $this->login();

        $role= DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'ASC')
            ->take(1)
            ->get();

        ($this->graphQL('
            query Roles($name: Mixed) {
                roles(filter: { column: NAME, operator: ILIKE, value: $name }) {
                    data {
                        id
                        name
                    }
                }
            }
        ', [
            'name' => 'Super Role',
        ]))->seeJson([
            'data' => [
                'roles' => [
                    'data' => [[
                        'id' => strval($role[0]->id),
                        'name' => strval($role[0]->name),
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryRolesWhereFilter(): void
    {
        $this->login();

        $role= DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        ($this->graphQL('
            query {
                roles(where:{column:GROUP_TYPE_ID, value: 1 }) {
                    data {
                        id
                        name
                    }
                }
            }
        ', [
            'id' => strval($role[0]->id),
        ]))->seeJson([
            'data' => [
                'roles' => [
                    'data' => [[
                        'id' => strval($role[0]->id),
                        'name' => strval($role[0]->name),
                    ]],
                ],
            ],
        ]);
    }

    public function testDeleteRole(): void
    {
        $this->login();

        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
            mutation DeleteRole($id: ID!) {
                deleteRole(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($role[0]->id),
        ]);

        $id = json_decode($this->response->getContent(), true);
        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteRole']['id'],
            ],
        ]);
    }

}
