<?php

use App\Models\Accounts;

class AccountsTest extends TestCase
{
    /**
     * Accounts Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateAccount()
    {
        $this->login();
        $this->graphQL('
            mutation (
                $currency_id:ID!
                $client_id:ID!
                $owner_id:ID!
                $account_number:String!
                $account_type:String!
                $payment_provider_id:ID!
                $commission_template_id:ID!
                $account_state:String!
                $account_name:String!
                $client_type:String!
            )
            {
                createAccount (
                    currency_id: $currency_id
                    client_id: $client_id
                    owner_id: $owner_id
                    account_number: $account_number
                    account_type: $account_type
                    payment_provider_id: $payment_provider_id
                    commission_template_id: $commission_template_id
                    account_state: $account_state
                    account_name: $account_name
                    is_primary: true
                    client_type: $client_type
                )
                {
                    id
                }
            }
        ', [
            'currency_id' =>  2,
            'client_id' => 1,
            'owner_id' => 2,
            'account_number' => '2566'.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'account_type' => 'Business',
            'payment_provider_id' => 1,
            'commission_template_id' => 1,
            'account_state' => '1',
            'account_name' => 'Test_'.\Illuminate\Support\Str::random(3),
            'client_type' => 'Individual',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createAccount' => [
                    'id' => $id['data']['createAccount']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateAccount()
    {
        $this->login();
        $account = \App\Models\Accounts::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation UpdateAccount(
                $id: ID!
                $currency_id: ID!
                $client_id: ID!
                $client_type: String!
                $owner_id: ID!
                $account_type: String!
                $payment_provider_id: ID!
                $commission_template_id: ID!
                $account_state: String!
                $account_name: String!
            )
            {
                updateAccount (
                    id: $id
                    currency_id: $currency_id
                    client_id: $client_id
                    client_type: $client_type
                    owner_id: $owner_id
                    account_type: $account_type
                    payment_provider_id: $payment_provider_id
                    commission_template_id: $commission_template_id
                    account_state: $account_state
                    account_name: $account_name
                    is_primary: true
                )
                {
                    id
                    account_name
                }
            }
        ', [
            'id' => strval($account[0]->id),
            'currency_id' =>  2,
            'client_id' => 1,
            'client_type' => 'Individual',
            'owner_id' => 2,
            'account_type' => 'Business',
            'payment_provider_id' => 1,
            'commission_template_id' => 1,
            'account_state' => '1',
            'account_name' => 'Test_'.\Illuminate\Support\Str::random(3),
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
        $accounts = Accounts::orderBy('id')->take(1)->get();
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
        $account = \App\Models\Accounts::orderBy('id', 'DESC')->get();
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
        $account = \App\Models\Accounts::where('owner_id', 2)->get();
        $this->graphQL('
        query {
            accounts(where: { column: OWNER_ID, value: 2}) {
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

    public function testDeleteApplicantIndividual()
    {
        $this->login();
        $account = \App\Models\Accounts::orderBy('id', 'DESC')->take(1)->get();
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
