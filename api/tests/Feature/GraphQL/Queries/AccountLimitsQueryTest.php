<?php

namespace Feature\GraphQL\Queries;

use App\Models\AccountLimit;
use Tests\TestCase;

class AccountLimitsQueryTest extends TestCase
{
    public function testQueryAccountLimitsNoAuth(): void
    {
        $this->graphQL('
            {
                accountLimits {
                    data {
                        id
                        account_id
                        currency_id
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryAccountLimit(): void
    {
        $accountLimit = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query AccountLimit($id: ID) {
                    accountLimit(id: $id) {
                            id
                            account_id
                            currency_id
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
                'accountLimit' => [
                    'id' => (string) $accountLimit->id,
                    'account_id' => (string) $accountLimit->account_id,
                    'currency_id' => (string) $accountLimit->currency_id,
                ],
            ],
        ]);
    }

    public function testQueryTicketsList(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')->get();

        $this->postGraphQL(
            [
                'query' => '
                {
                    accountLimits (orderBy: { column: ID, order: ASC }) {
                        data {
                            id
                            account_id
                            currency_id
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $accountLimits[0]->id,
            'account_id' => (string) $accountLimits[0]->account_id,
            'currency_id' => (string) $accountLimits[0]->currency_id,
        ]);
    }

    public function testQueryAccountLimitsWithFilterByAccountId(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'currency_id' => (string) $accountLimits->currency_id,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountLimits($id: Mixed) {
                    accountLimits (
                        filter: { column: ACCOUNT_ID, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            currency_id
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

    public function testQueryAccountLimitsWithFilterByPeriodCount(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'currency_id' => (string) $accountLimits->currency_id,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountLimits($id: Mixed) {
                    accountLimits (
                        filter: { column: PERIOD_COUNT, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            currency_id
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->period_count,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountLimitsWithFilterByCurrency(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'currency_id' => (string) $accountLimits->currency_id,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountLimits($id: Mixed) {
                    accountLimits (
                        filter: { column: HAS_CURRENCY_MIXED_ID_OR_NAME, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            currency_id
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->currency_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountLimitsWithFilterByCommissionTemplateLimitType(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'currency_id' => (string) $accountLimits->currency_id,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountLimits($id: Mixed) {
                    accountLimits (
                        filter: { column: HAS_COMMISSION_TEMPLATE_LIMIT_TYPE_MIXED_ID_OR_NAME, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            currency_id
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->commission_template_limit_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountLimitsWithFilterByCommissionTemplateLimitTransferDirection(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'currency_id' => (string) $accountLimits->currency_id,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountLimits($id: Mixed) {
                    accountLimits (
                        filter: { column: HAS_COMMISSION_TEMPLATE_LIMIT_TRANSFER_DIRECTION_MIXED_ID_OR_NAME, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            currency_id
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->commission_template_limit_transfer_direction_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountLimitsWithFilterByCommissionTemplateLimitPeriod(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'currency_id' => (string) $accountLimits->currency_id,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountLimits($id: Mixed) {
                    accountLimits (
                        filter: { column: HAS_COMMISSION_TEMPLATE_LIMIT_PERIOD_MIXED_ID_OR_NAME, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            currency_id
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->commission_template_limit_period_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryAccountLimitsWithFilterByCommissionTemplateLimitActionType(): void
    {
        $accountLimits = AccountLimit::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $accountLimits->id,
            'account_id' => (string) $accountLimits->account_id,
            'currency_id' => (string) $accountLimits->currency_id,
        ];

        $this->postGraphQL(
            [
                'query' => 'query AccountLimits($id: Mixed) {
                    accountLimits (
                        filter: { column: HAS_COMMISSION_TEMPLATE_LIMIT_ACTION_TYPE_MIXED_ID_OR_NAME, value: $id }
                    ) {
                        data {
                            id
                            account_id
                            currency_id
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $accountLimits->commission_template_limit_action_type_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }
}
