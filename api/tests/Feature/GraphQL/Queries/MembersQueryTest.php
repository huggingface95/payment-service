<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

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

        $member = DB::connection('pgsql_test')->table('members')->orderBy('id', 'ASC')->get();

        $this->graphQL('
            query Member($id:ID!){
                member(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($member[0]->id),
        ])->seeJson([
            'data' => [
                'member' => [
                    'id' => strval($member[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryMembersOrderBy(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')->table('members')->orderBy('id', 'ASC')->get();

        $this->graphQL('
        query {
            members(orderBy: { column: ID, order: ASC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($member[0]->id),
            ],
        ]);
    }

    public function testQueryMembersWhere(): void
    {
        $this->login();

        $member = DB::connection('pgsql_test')->table('members')->orderBy('id', 'ASC')->get();

        $this->graphQL('
        query {
            members(where: { column: ID, value: 2}) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($member[0]->id),
            ],
        ]);
    }
}
