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

    public function testQueryMember(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->orderBy('id', 'ASC')
            ->get();

        $this->graphQL('
            query Member($id:ID!){
                member(id: $id) {
                    id
                }
            }
        ', [
            'id' => (string) $member[0]->id,
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
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->orderBy('id', 'ASC')
            ->get();

        $this->graphQL('
        query {
            members(orderBy: { column: ID, order: ASC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => (string) $member[0]->id,
            ],
        ]);
    }

    public function testQueryMembersWhere(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->orderBy('id', 'ASC')
            ->get();

        $this->graphQL('
        query Members ($id: Mixed) {
            members(where: { column: ID, value: $id}) {
                data {
                    id
                }
                }
        }', [
            'id' => (string) $member[0]->id
        ])->seeJsonContains([
            [
                'id' => (string) $member[0]->id,
            ],
        ]);
    }

   public function testQueryMembersByDepartmentPosition(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->graphQL('
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
            }
        ', [
            'id' => $member->department_position_id
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersById(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->graphQL('
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
            }
        ', [
            'id' => $member->id
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByFullName(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->graphQL('
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
            }
        ', [
            'name' => $member->fullname
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByCompanyId(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->graphQL('
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
            }
        ', [
            'id' => $member->company_id
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByEmail(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->graphQL('
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
            }
        ', [
            'email' => $member->email
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }

    public function testQueryMembersByDepartmentPositionId(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')
            ->table('members')
            ->first();

        $this->graphQL('
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
            }
        ', [
            'id' => $member->department_position_id
        ])->seeJsonContains([
            'id' => (string) $member->id,
            'first_name' => (string) $member->first_name,
            'email' => (string) $member->email,
        ]);
    }
}
