<?php

namespace Feature\GraphQL\Mutations;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountLimitsMutationTest extends TestCase
{
    /**
     * Account Limits Mutation Testing
     *
     * @return void
     */
    public function testCreateAccountLimitNoAuth(): void
    {
        $this->graphQL('
            mutation CreateAccountLimit(
                    $account_id: ID!
                    $commission_template_limit_type_id: ID!
                    $commission_template_limit_transfer_direction_id: ID!
                    $amount: Decimal!
                    $currency_id: ID
                    $commission_template_limit_period_id: ID!
                    $commission_template_limit_action_type_id: ID!
                    $period_count: ID
                ) {
                createAccountLimit(
                    account_id: $account_id
                    commission_template_limit_type_id: $commission_template_limit_type_id
                    commission_template_limit_transfer_direction_id: $commission_template_limit_transfer_direction_id
                    amount: $amount
                    currency_id: $currency_id
                    commission_template_limit_period_id: $commission_template_limit_period_id
                    commission_template_limit_action_type_id: $commission_template_limit_action_type_id
                    period_count: $period_count
                )
              {
                id
              }
           }
        ', [
            'account_id' => 1,
            'commission_template_limit_type_id' => 1,
            'commission_template_limit_transfer_direction_id' => 1,
            'amount' => (float) 100000.00000,
            'currency_id' => 1,
            'commission_template_limit_period_id' => 1,
            'commission_template_limit_action_type_id' => 1,
            'period_count' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateAccountLimit(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                    mutation CreateAccountLimit(
                        $account_id: ID!
                        $commission_template_limit_type_id: ID!
                        $commission_template_limit_transfer_direction_id: ID!
                        $amount: Decimal!
                        $currency_id: ID
                        $commission_template_limit_period_id: ID!
                        $commission_template_limit_action_type_id: ID!
                        $period_count: ID
                    ) {
                    createAccountLimit(
                        account_id: $account_id
                        commission_template_limit_type_id: $commission_template_limit_type_id
                        commission_template_limit_transfer_direction_id: $commission_template_limit_transfer_direction_id
                        amount: $amount
                        currency_id: $currency_id
                        commission_template_limit_period_id: $commission_template_limit_period_id
                        commission_template_limit_action_type_id: $commission_template_limit_action_type_id
                        period_count: $period_count
                    )
                  {
                    id
                  }
                }',
                'variables' => [
                    'account_id' => 1,
                    'commission_template_limit_type_id' => 1,
                    'commission_template_limit_transfer_direction_id' => 1,
                    'amount' => 10000,
                    'currency_id' => 1,
                    'commission_template_limit_period_id' => 1,
                    'commission_template_limit_action_type_id' => 1,
                    'period_count' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createAccountLimit']['id'],
            ],
        ]);
    }

    public function testUpdateAccountLimit(): void
    {
        $accountLimit = DB::connection('pgsql_test')
            ->table('account_limits')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateAccountLimit(
                    $id: ID!
                    $commission_template_limit_type_id: ID
                    $commission_template_limit_transfer_direction_id: ID
                    $currency_id: ID
                    $commission_template_limit_period_id: ID
                    $commission_template_limit_action_type_id: ID
                    $period_count: ID
                )
                {
                    updateAccountLimit (
                        id: $id
                        commission_template_limit_type_id: $commission_template_limit_type_id
                        commission_template_limit_transfer_direction_id: $commission_template_limit_transfer_direction_id
                        currency_id: $currency_id
                        commission_template_limit_period_id: $commission_template_limit_period_id
                        commission_template_limit_action_type_id: $commission_template_limit_action_type_id
                        period_count: $period_count
                    )
                    {
                        id
                        account_id
                        currency_id
                    }
                }',
                'variables' => [
                    'id' => $accountLimit->id,
                    'commission_template_limit_type_id' => 1,
                    'commission_template_limit_transfer_direction_id' => 1,
                    'currency_id' => 1,
                    'commission_template_limit_period_id' => 1,
                    'commission_template_limit_action_type_id' => 1,
                    'period_count' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateAccountLimit' => [
                    'id' => $id['data']['updateAccountLimit']['id'],
                    'account_id' => $id['data']['updateAccountLimit']['account_id'],
                    'currency_id' => $id['data']['updateAccountLimit']['currency_id'],
                ],
            ],
        ]);
    }

    public function testDeleteAccountLimit(): void
    {
        $accountLimit = DB::connection('pgsql_test')
            ->table('account_limits')
            ->orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteAccountLimit(
                    $id: ID!
                )
                {
                    deleteAccountLimit (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimit->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteAccountLimit' => [
                    'id' => $id['data']['deleteAccountLimit']['id'],
                ],
            ],
        ]);
    }
}
