<?php

namespace Tests;

use App\Models\Account;
use App\Models\Payments;

class PaymentsQueryTest extends TestCase
{
    public function testQueryPaymentsNoAuth(): void
    {
        $this->graphQL('
        query {
            payments {
                data {
                    id
                    amount
                }
            }
        }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPaymentsList(): void
    {
        $payments = Payments::get();

        $expect = [];
        foreach ($payments as $payment) {
            $expect['data']['payments']['data'][] = [
                'id' => (string) $payment['id'],
                'amount' => (string) number_format($payment->amount, 5, '.', ''),
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                query {
                    payments {
                        data {
                            id
                            amount
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson($expect);
    }

    public function testQueryPayment(): void
    {
        $payment = Payments::first();

        $this->postGraphQL(
            [
                'query' => '
                query payment($id: ID!) {
                    payment(id: $id) {
                        id
                        amount
                    }
                }',
                'variables' => [
                    'id' => $payment->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'payment' => [
                    'id' => (string) $payment->id,
                    'amount' => (string) number_format($payment->amount, 5, '.', ''),
                ],
            ],
        ]);
    }

    public function testQueryPaymentFilterByAccountNumber(): void
    {
        $payment = Payments::first();

        $account = Account::where('id', $payment->account_id)->first();

        $this->postGraphQL(
            [
                'query' => '
                query Payments($id: Mixed) {
                    payments(
                        filter: {
                            column: HAS_ACCOUNT_FILTER_BY_ACCOUNT_NUMBER
                            operator: ILIKE
                            value: $id
                          }
                    ) {
                        data {
                            id
                            amount
                        }
                    }
                }',
                'variables' => [
                    'id' => $account->account_number,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $payment->id,
            'amount' => (string) number_format($payment->amount, 5, '.', ''),
        ]);
    }

    /**
     * @dataProvider provide_testQueryPaymentsWithFilterByCondition
     */
    public function testQueryPaymentsWithFilterByCondition($cond, $value): void
    {
        $payments = Payments::where($cond, $value)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($payments as $payment) {
            $expect['data']['payments']['data'][] = [
                'id' => (string) $payment->id,
                'status_id' => (string) $payment->status_id,
            ];
        }

        $this->postGraphQL(
            [
                'query' => 'query Payments($id: Mixed) {
                    payments (
                        filter: { column: '.strtoupper($cond).', operator: EQ, value: $id }
                    ) {
                        data {
                            id
                            status_id
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($expect);
    }

    public function provide_testQueryPaymentsWithFilterByCondition()
    {
        return [
            ['id', '1'],
            ['company_id', '1'],
            ['payment_provider_id', '1'],
            ['operation_type_id', '1'],
            ['urgency_id', '1'],
            ['status_id', '1'],
        ];
    }
}
