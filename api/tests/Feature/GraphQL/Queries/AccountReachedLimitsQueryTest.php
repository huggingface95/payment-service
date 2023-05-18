<?php

namespace Feature\GraphQL\Queries;

use App\Models\AccountReachedLimit;
use Tests\TestCase;

class AccountReachedLimitsQueryTest extends TestCase
{
    public function testQueryAccountReachedLimitsNoAuth(): void
    {
        $this->graphQL('
            {
                accountReachedLimits {
                    data {
                        id
                        account_id
                        group_type
                        client_name
                        client_type
                        transfer_direction
                        limit_type
                        limit_value
                        limit_currency
                        period
                        amount
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryAccountReachedLimit(): void
    {
        $accountLimit = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query AccountReachedLimit($id: ID) {
                    accountReachedLimit(id: $id) {
                        id
                        account_id
                        group_type
                        client_name
                        client_type
                        transfer_direction
                        limit_type
                        limit_value
                        limit_currency
                        period
                        amount
                    }
                }',
                'variables' => [
                    'id' => $accountLimit->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'accountReachedLimit' => [
                    'id' => (string) $accountLimit->id,
                    'account_id' => (string) $accountLimit->account_id,
                    'group_type' => (string) $accountLimit->group_type,
                    'client_name' => (string) $accountLimit->client_name,
                    'client_type' => (string) $accountLimit->client_type,
                    'transfer_direction' => (string) $accountLimit->transfer_direction,
                    'limit_type' => (string) $accountLimit->limit_type,
                    'limit_value' => $accountLimit->limit_value,
                    'limit_currency' => (string) $accountLimit->limit_currency,
                    'period' => $accountLimit->period,
                    'amount' => (string) $accountLimit->amount,
                ],
            ],
        ]);
    }

    public function testQueryAccountReachedLimitsList(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')->get();

        foreach ($accountLimits as $accountLimit) {
            $data[] = [
                'id' => (string) $accountLimit->id,
                'account_id' => (string) $accountLimit->account_id,
                'group_type' => (string) $accountLimit->group_type,
                'client_name' => (string) $accountLimit->client_name,
                'client_type' => (string) $accountLimit->client_type,
                'transfer_direction' => (string) $accountLimit->transfer_direction,
                'limit_type' => (string) $accountLimit->limit_type,
                'limit_value' => $accountLimit->limit_value,
                'limit_currency' => (string) $accountLimit->limit_currency,
                'period' => $accountLimit->period,
                'amount' => (string) $accountLimit->amount,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    accountReachedLimits (orderBy: { column: ID, order: ASC }) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'accountReachedLimits' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryAccountReachedLimitsWithFilterByAccountId(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'group_type' => (string) $accountLimits->group_type,
            'client_name' => (string) $accountLimits->client_name,
            'client_type' => (string) $accountLimits->client_type,
            'transfer_direction' => (string) $accountLimits->transfer_direction,
            'limit_type' => (string) $accountLimits->limit_type,
            'limit_value' => $accountLimits->limit_value,
            'limit_currency' => (string) $accountLimits->limit_currency,
            'period' => $accountLimits->period,
            'amount' => (string) $accountLimits->amount,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountReachedLimits($id: Mixed) {
                    accountReachedLimits (
                        filter: { column: ACCOUNT_ID, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->account_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountReachedLimitsWithFilterByLimitCurrency(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'group_type' => (string) $accountLimits->group_type,
            'client_name' => (string) $accountLimits->client_name,
            'client_type' => (string) $accountLimits->client_type,
            'transfer_direction' => (string) $accountLimits->transfer_direction,
            'limit_type' => (string) $accountLimits->limit_type,
            'limit_value' => $accountLimits->limit_value,
            'limit_currency' => (string) $accountLimits->limit_currency,
            'period' => $accountLimits->period,
            'amount' => (string) $accountLimits->amount,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountReachedLimits($id: Mixed) {
                    accountReachedLimits (
                        filter: { column: LIMIT_CURRENCY, operator: ILIKE, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->limit_currency,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountReachedLimitsWithFilterByLimitType(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'group_type' => (string) $accountLimits->group_type,
            'client_name' => (string) $accountLimits->client_name,
            'client_type' => (string) $accountLimits->client_type,
            'transfer_direction' => (string) $accountLimits->transfer_direction,
            'limit_type' => (string) $accountLimits->limit_type,
            'limit_value' => $accountLimits->limit_value,
            'limit_currency' => (string) $accountLimits->limit_currency,
            'period' => $accountLimits->period,
            'amount' => (string) $accountLimits->amount,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountReachedLimits($id: Mixed) {
                    accountReachedLimits (
                        filter: { column: LIMIT_TYPE, operator: ILIKE, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->limit_type,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountReachedLimitsWithFilterByLimitValue(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'group_type' => (string) $accountLimits->group_type,
            'client_name' => (string) $accountLimits->client_name,
            'client_type' => (string) $accountLimits->client_type,
            'transfer_direction' => (string) $accountLimits->transfer_direction,
            'limit_type' => (string) $accountLimits->limit_type,
            'limit_value' => $accountLimits->limit_value,
            'limit_currency' => (string) $accountLimits->limit_currency,
            'period' => $accountLimits->period,
            'amount' => (string) $accountLimits->amount,
        ];

        $this->postGraphQL(
            [
                'query' => '{
                    accountReachedLimits (
                        filter: { column: LIMIT_VALUE, operator: EQ, value: 500 }
                    ) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountReachedLimitsWithFilterByTransferDirection(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'group_type' => (string) $accountLimits->group_type,
            'client_name' => (string) $accountLimits->client_name,
            'client_type' => (string) $accountLimits->client_type,
            'transfer_direction' => (string) $accountLimits->transfer_direction,
            'limit_type' => (string) $accountLimits->limit_type,
            'limit_value' => $accountLimits->limit_value,
            'limit_currency' => (string) $accountLimits->limit_currency,
            'period' => $accountLimits->period,
            'amount' => (string) $accountLimits->amount,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountReachedLimits($id: Mixed) {
                    accountReachedLimits (
                        filter: { column: TRANSFER_DIRECTION, operator: ILIKE, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->transfer_direction,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountReachedLimitsWithFilterByPeriod(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'group_type' => (string) $accountLimits->group_type,
            'client_name' => (string) $accountLimits->client_name,
            'client_type' => (string) $accountLimits->client_type,
            'transfer_direction' => (string) $accountLimits->transfer_direction,
            'limit_type' => (string) $accountLimits->limit_type,
            'limit_value' => $accountLimits->limit_value,
            'limit_currency' => (string) $accountLimits->limit_currency,
            'period' => $accountLimits->period,
            'amount' => (string) $accountLimits->amount,
        ];

        $this->postGraphQL(
            [
                'query' => '{
                    accountReachedLimits (
                        filter: { column: PERIOD, operator: EQ, value: 5 }
                    ) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountReachedLimitsWithFilterByAmount(): void
    {
        $accountLimits = AccountReachedLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'group_type' => (string) $accountLimits->group_type,
            'client_name' => (string) $accountLimits->client_name,
            'client_type' => (string) $accountLimits->client_type,
            'transfer_direction' => (string) $accountLimits->transfer_direction,
            'limit_type' => (string) $accountLimits->limit_type,
            'limit_value' => $accountLimits->limit_value,
            'limit_currency' => (string) $accountLimits->limit_currency,
            'period' => $accountLimits->period,
            'amount' => (string) $accountLimits->amount,
        ];

        $this->postGraphQL(
            [
                'query' => '{
                    accountReachedLimits (
                        filter: { column: AMOUNT, operator: EQ, value: 1000000 }
                    ) {
                        data {
                            id
                            account_id
                            group_type
                            client_name
                            client_type
                            transfer_direction
                            limit_type
                            limit_value
                            limit_currency
                            period
                            amount
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }
}
