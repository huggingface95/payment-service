<?php

namespace Tests\Feature\GraphQL\Queries;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountsQueryTest extends TestCase
{

    public function testQueryAccounts(): void
    {
        $this->login();

        $accounts = DB::connection('pgsql_test')->table('accounts')->orderBy('id', 'DESC')->take(1)->get();

        ($this->graphQL('
            query Account($id:ID!){
                account(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($accounts[0]->id),
        ]))->seeJson([
            'data' => [
                'account' => [
                    'id' => strval($accounts[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryAccountsOrderBy(): void
    {
        $this->login();

        $account = DB::connection('pgsql_test')->table('accounts')->orderBy('id', 'DESC')->take(1)->get();

        $this->graphQL('
        query {
            accounts(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($account[0]->id),
            ],
        ]);
    }

    public function testQueryAccountsWhere(): void
    {
        $this->login();

        $account = DB::connection('pgsql_test')->table('accounts')->orderBy('id', 'DESC')->take(1)->get();

        $this->graphQL('
        query Accounts($owner: String) {
            accounts (query:{owner:$owner})
                {
                data{
                    id
                }
                }
        }', [
            'owner' => strval(1),
        ])->seeJsonContains([
            [
                'id' => strval($account[0]->id),
            ],
        ]);
    }

}
