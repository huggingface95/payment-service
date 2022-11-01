<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class RolesQueryTest extends TestCase
{
    /**
     * Roles Query Testing
     *
     * @return void
     */

    public function testQueryRolesById(): void
    {
        $this->login();

        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
            query Role($id: ID!) {
                role(id: $id) {
                    id
                    name
                }
            }
        ', [
            'id' => strval($role[0]->id),
        ])->seeJson([
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

        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->first();

        $this->graphQL('
            query {
                roles(filter: { column: HAS_GROUP_TYPE_MIXED_ID_OR_NAME, value: 1 }) {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJsonContains([
            'id' => strval($role->id),
            'name' => strval($role->name),
        ]);
    }

    public function testQueryByCompanyId(): void
    {
        $this->login();

        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->first();

        $this->graphQL('
            query {
                roles(filter: { column: COMPANY_ID, value: 1 }) {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJsonContains([
            'id' => strval($role->id),
            'name' => strval($role->name),
        ]);
    }

    public function testQueryByRoleName(): void
    {
        $this->login();

        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'ASC')
            ->take(1)
            ->get();

        $this->graphQL('
            query Roles($name: Mixed) {
                roles(filter: { column: NAME, operator: ILIKE, value: $name }) {
                    data {
                        id
                        name
                    }
                }
            }
        ', [
            'name' => (string) $role[0]->name,
        ])->seeJson([
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

        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->first();

        $this->graphQL('
            query Roles($id:Mixed){
                roles(where:{column:GROUP_TYPE_ID, value: $id}) {
                    data {
                        id
                        name
                    }
                }
            }
            ', [
                'id' => $role->group_type_id,
        ])->seeJsonContains([
            'id' => strval($role->id),
            'name' => strval($role->name),
        ]);
    }
}
