<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class GroupsMutationTest extends TestCase
{
    /**
     * Groups Mutation Testing
     *
     * @return void
     */
    public function testCreateGroupNoAuth(): void
    {
        $seq = DB::table('group_role')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE group_role_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateGroup(
                $name: String!
                $group_type_id: ID!
                $role_id: ID
                $description: String
                $company_id: ID
            ) {
            createGroupSettings(
                name: $name
                description: $description
                group_type_id: $group_type_id
                role_id: $role_id
                company_id: $company_id
                is_active: true
            ) {
                id
                name
                role_id
                description
                is_active
            }
            }
        ', [
            'name' => 'Test Group Role Mutation',
            'group_type_id' => 2,
            'description' => 'Description Group Role',
            'role_id' => 2,
            'company_id' => 1,
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateGroup(): void
    {
        $seq = DB::table('group_role')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE group_role_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateGroup(
                    $name: String!
                    $group_type_id: ID!
                    $role_id: ID
                    $description: String
                    $company_id: ID
                ) {
                createGroupSettings(
                    name: $name
                    description: $description
                    group_type_id: $group_type_id
                    role_id: $role_id
                    company_id: $company_id
                    is_active: true
                ) {
                    id
                    name
                    role_id
                    description
                    is_active
                }
                }',
                'variables' => [
                    'name' => 'Test Group Role Mutation',
                    'group_type_id' => 2,
                    'description' => 'Description Group Role',
                    'role_id' => 2,
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
                'id' => $id['data']['createGroupSettings']['id'],
                'name' => $id['data']['createGroupSettings']['name'],
                'description' => $id['data']['createGroupSettings']['description'],
                'is_active' => $id['data']['createGroupSettings']['is_active'],
                'role_id' => $id['data']['createGroupSettings']['role_id'],
            ],
        ]);
    }

    public function testUpdateGroup(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateGroup(
                    $id: ID!
                    $name: String!
                    $description: String
                    $group_type_id: ID!
                ) {
                updateGroupSettings(
                    id: $id
                    name: $name
                    description: $description
                    group_type_id: $group_type_id
                ) {
                    id
                    name
                    role_id
                    description
                    is_active
                }
                }',
                'variables' => [
                    'id' => (string) $group[0]->id,
                    'name' => 'Test updated group',
                    'description' => 'Descr updated group',
                    'group_type_id' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateGroupSettings' => [
                    'id' => $id['data']['updateGroupSettings']['id'],
                    'name' => $id['data']['updateGroupSettings']['name'],
                    'role_id' => $id['data']['updateGroupSettings']['role_id'],
                    'description' => $id['data']['updateGroupSettings']['description'],
                    'is_active' => $id['data']['updateGroupSettings']['is_active'],
                ],
            ],
        ]);
    }

    public function testDeleteGroup(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteGroup($id: ID!) {
                    deleteGroup(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $group[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteGroup']['id'],
            ],
        ]);
    }
}
