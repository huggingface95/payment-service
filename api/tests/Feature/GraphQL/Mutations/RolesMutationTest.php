<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class RolesMutationTest extends TestCase
{
    /**
     * Roles Mutation Testing
     *
     * @return void
     */
    public function testCreateRoleNoAuth(): void
    {
        $seq = DB::table('roles')
                ->max('id') + 1;

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
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateRole(): void
    {
        $seq = DB::table('roles')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE roles_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
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
            }',
                'variables' => [
                    'name' => 'Test Role Test',
                    'group_type_id' => 1,
                    'description' => 'Test Role Desc',
                    'company_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createRole']['id'],
            ],
        ]);
    }

    public function testUpdateRole(): void
    {
        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => (string) $role[0]->id,
                    'name' => 'Test updated role',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateRole' => [
                    'id' => $id['data']['updateRole']['id'],
                ],
            ],
        ]);
    }

    public function testDeleteRole(): void
    {
        $role = DB::connection('pgsql_test')
            ->table('roles')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteRole($id: ID!) {
                    deleteRole(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $role[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteRole']['id'],
            ],
        ]);
    }
}
