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

    public function testQueryGroupType(): void
    {
        $this->login();

        $group= DB::connection('pgsql_test')
            ->table('group_types')
            ->first();

        $this->graphQL('
            query GetGroupType($id: ID) {
                group_type(id: $id) {
                    id
                    name
                }
            }
        ', [
            'id' => strval($group->id),
        ])->seeJson([
            'data' => [
                'group_type' => [
                    'id' => strval($group->id),
                    'name' => strval($group->name),
                ],
            ],
        ]);
    }

    public function testQueryGroupListById(): void
    {
        $this->login();

        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->graphQL('
            query GetGroupType($id: ID) {
                groupList(query: { id: $id }) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'id' => strval($group->id),
        ])->seeJson([
            'data' => [
                'groupList' => [
                    'data' => [[
                        'id' => strval($group->id),
                        'name' => strval($group->name),
                        'description' => strval($group->description),
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryGroupListByCompanyId(): void
    {
        $this->login();

        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->graphQL('
            query GetGroupByCompanyId($id: ID) {
                groupList(query: { company_id: $id }) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'id' => strval($group->id),
        ])->seeJsonContains([
           'id' => strval($group->id),
           'name' => strval($group->name),
           'description' => strval($group->description),
        ]);
    }

    public function testQueryGroupListByPaymentProviderId(): void
    {
        $this->login();

        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->graphQL('
            query GetGroup($id: ID) {
                groupList(query: { payment_provider_id: $id }) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'id' => strval($group->id),
        ])->seeJsonContains([
            'id' => strval($group->id),
            'name' => strval($group->name),
            'description' => strval($group->description),
        ]);
    }

    public function testQueryGroupListByRoleId(): void
    {
        $this->login();

        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->graphQL('
            query GetGroup($id: ID) {
                groupList(query: { role_id: $id }) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'id' => strval($group->role_id),
        ])->seeJson([
            'data' => [
                'groupList' => [
                    'data' => [[
                        'id' => strval($group->id),
                        'name' => strval($group->name),
                        'description' => strval($group->description),
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryGroupListByName(): void
    {
        $this->login();

        $group = DB::connection('pgsql_test')
            ->table('group_role')
            ->first();

        $this->graphQL('
            query GetGroup($name: String) {
                groupList(query: { name: $name }) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'name' => strval($group->name),
        ])->seeJson([
            'data' => [
                'groupList' => [
                    'data' => [[
                        'id' => strval($group->id),
                        'name' => strval($group->name),
                        'description' => strval($group->description),
                    ]],
                ],
            ],
        ]);
    }
}
