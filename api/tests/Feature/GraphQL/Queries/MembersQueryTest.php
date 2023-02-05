<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use function Aws\boolean_value;

class MembersQueryTest extends TestCase
{
    /**
     * Members Qurty Testing
     *
     * @return void
     */

    public function testQueryMembersNoAuth(): void
    {
        $this->graphQL('
            {
                members {
                    data {
                         id
                         fullname
                         email
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryMember(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query Member($id:ID!){
                    member(id: $id) {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $member[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson([
            'data' => [
                'member' => [
                    'id' => (string) $member[0]->id,
                ],
            ],
        ]);
    }

    public function testQueryMembersOrderBy(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query {
                    members(orderBy: { column: ID, order: ASC }) {
                        data {
                            id
                        }
                        }
                }',
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]
        )->seeJsonContains([
            [
                'id' => (string) $member[0]->id,
            ],
        ]);
    }

    public function testQueryMembersWhere(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL([
            'query' => '
                query Members ($id: Mixed) {
                    members(where: { column: ID, value: $id}) {
                        data {
                            id
                        }
                        }
                }',
            'variables' => [
                'id' => (string) $member[0]->id
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            [
                'id' => (string) $member[0]->id,
            ],
        ]);
    }

   public function testQueryMembersByDepartmentPosition(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->postGraphQL([
            'query' => '
                query Members($id: Mixed) {
                    members(
                        filter: {
                            column: HAS_DEPARTMENT_FILTER_BY_ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            first_name
                            email
                        }
                    }
                }',
            'variables' => [
                'id' => $member->department_position_id
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersById(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->postGraphQL([
            'query' => '
                query Members($id: Mixed) {
                    members(
                        filter: {
                            column: ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            first_name
                            email
                        }
                    }
                }',
            'variables' => [
                'id' => $member->id
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByFullName(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->postGraphQL([
            'query' => '
                query Members($name: Mixed) {
                    members(
                        filter: {
                            column: FULLNAME
                            operator: ILIKE
                            value: $name
                        }
                    ) {
                        data {
                            id
                            first_name
                            email
                        }
                    }
                }',
            'variables' => [
                'name' => $member->fullname
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByCompanyId(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->postGraphQL([
            'query' => '
                query Members($id: Mixed) {
                    members(
                        filter: {
                            column: COMPANY_ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            first_name
                            email
                        }
                    }
                }',
            'variables' => [
                'id' => $member->company_id
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByEmail(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->postGraphQL([
            'query' => '
                query Members($email: Mixed) {
                    members(
                        filter: {
                            column: EMAIL
                            operator: ILIKE
                            value: $email
                        }
                    ) {
                        data {
                            id
                            first_name
                            email
                        }
                    }
                }',
            'variables' => [
                'email' => $member->email
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByDepartmentPositionId(): void
    {
        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->postGraphQL([
            'query' => '
                query Members($id: Mixed) {
                    members(
                        filter: {
                            column: DEPARTMENT_POSITION_ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            first_name
                            email
                        }
                    }
                }',
            'variables' => [
                'id' => $member->department_position_id
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }
}
