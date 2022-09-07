<?php

namespace Tests;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountsTest extends TestCase
{
    /**
     * Account Testing
     *
     * @return void
     */
    public function testCreateAccount()
    {
        $this->login();
        $this->graphQL('
            mutation CreateAccount(
                    $company_id: ID!
                    $currency_id: ID!
                    $commission_template_id: ID!
                    $owner_id: ID!
                    $account_number: String
                    $account_name: String!
                    $payment_provider_id: ID!
                    $payment_system_id: ID!
                    $group_type_id: ID!
                    $group_role_id: ID!
                ) {
                createAccount(
                  input:{
                    company_id: $company_id
                    currency_id: $currency_id
                    owner_id: $owner_id
                    account_number: $account_number
                    commission_template_id: $commission_template_id
                    account_name: $account_name
                    group_type_id: $group_type_id
                    group_role_id: $group_role_id
                    payment_system_id: $payment_system_id
                    payment_provider_id: $payment_provider_id
                    is_primary: true
                  }
                )
              {
                 id
              }
           }
        ', [
            'company_id' => 1,
            'currency_id' => 1,
            'owner_id' => 1,
            'account_number' => '2566'.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'commission_template_id' => 1,
            'account_name' => 'Test_'.\Illuminate\Support\Str::random(3),
            'group_type_id' => 2,
            'group_role_id' => 1,
            'payment_system_id' => 1,
            'payment_provider_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createAccount' => [
                    [
                        'id' => $id['data']['createAccount'][0]['id'],
                    ],
                ],
            ],
        ]);
    }

    public function testUpdateAccount()
    {
        $this->login();
        $account = DB::connection('pgsql_test')->table('accounts')->orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation UpdateAccount(
                $id: ID!
                $account_state_id: ID!
                $account_name: String!
            )
            {
                updateAccount (
                    id: $id
                    account_state_id: $account_state_id
                    account_name: $account_name
                    is_primary: false
                )
                {
                    id
                    account_name
                }
            }
        ', [
            'id' => strval($account[0]->id),
            'account_state_id' => '1',
            'account_name' => 'Test_update_'.\Illuminate\Support\Str::random(3),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateAccount' => [
                    'id' => $id['data']['updateAccount']['id'],
                    'account_name' => $id['data']['updateAccount']['account_name'],
                ],
            ],
        ]);
    }

    public function testQueryAccounts()
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

    public function testQueryAccountsOrderBy()
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

    public function testQueryAccountsWhere()
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

    public function testDeleteApplicantIndividual()
    {
        $this->login();
        $account = \App\Models\Account::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation DeleteAccount(
                $id: ID!
            )
            {
                deleteAccount (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($account[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteAccount' => [
                    'id' => $id['data']['deleteAccount']['id'],
                ],
            ],
        ]);
    }
}
