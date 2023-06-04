<?php

namespace Tests\Feature\GraphQL\Mutations;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountsMutationTest extends TestCase
{
    /**
     * Account Mutation Testing
     *
     * @return void
     */
    public function testCreateAccountNoAuth(): void
    {
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
            'owner_id' => 2,
            'account_number' => '2566'.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'commission_template_id' => 1,
            'account_name' => 'Test_account_'.\Illuminate\Support\Str::random(6),
            'group_type_id' => 2,
            'group_role_id' => 1,
            'payment_system_id' => 1,
            'payment_provider_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateAccount(): void
    {
        $this->postGraphQL(
            [
                'query' => '
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
               }',
                'variables' => [
                    'company_id' => 1,
                    'currency_id' => 1,
                    'owner_id' => 2,
                    'account_number' => '2566'.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'commission_template_id' => 1,
                    'account_name' => 'Test_account_'.\Illuminate\Support\Str::random(6),
                    'group_type_id' => 2,
                    'group_role_id' => 1,
                    'payment_system_id' => 1,
                    'payment_provider_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createAccount'][0]['id'],
            ],
        ]);
    }

    public function testUpdateAccount(): void
    {
        $account = DB::connection('pgsql_test')
            ->table('accounts')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => (string) $account->id,
                    'account_state_id' => '1',
                    'account_name' => 'Test_update_'.\Illuminate\Support\Str::random(3),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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

    public function testDeleteAccount(): void
    {
        $account = DB::connection('pgsql_test')
            ->table('accounts')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => (string) $account->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteAccount' => [
                    'id' => $id['data']['deleteAccount']['id'],
                ],
            ],
        ]);
    }

    public function testGenerateIban(): void
    {
        $account = DB::connection('pgsql_test')
            ->table('accounts')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                mutation GenerateIban(
                    $id: ID!
                )
                {
                    generateIban (
                        id: $id
                    )
                    {
                        status
                        message
                    }
                }',
                'variables' => [
                    'id' => (string) $account->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'generateIban' => [
                    'status' => $id['data']['generateIban']['status'],
                    'message' => $id['data']['generateIban']['message'],
                ],
            ],
        ]);
    }
}
