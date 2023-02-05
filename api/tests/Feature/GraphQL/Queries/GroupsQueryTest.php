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
        $group= DB::connection('pgsql_test')
            ->table('group_types')
            ->first();

        $this->postGraphQL([
            'query' => '
                query GetGroupType($id: ID) {
                    group_type(id: $id) {
                        id
                        name
                    }
                }',
            'variables' => [
                'id' => (string) $group->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
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
            ->table('group_role')
            ->first();

        $this->postGraphQL([
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
            ]
    ],
    [
        "Authorization" => "Bearer " . $this->login()
    ])->seeJson([
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
            ->table('group_role')
            ->first();

        $this->postGraphQL([
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
           'id' => (string) $group->id,
           'name' => (string) $group->name,
           'description' => (string) $group->description,
        ]);
    }

    public function testQueryGroupListByRoleId(): void
    {
        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->postGraphQL([
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
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
            ->table('group_role')
            ->first();

        $this->postGraphQL([
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
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
}
