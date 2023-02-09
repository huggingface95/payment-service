<?php

namespace Tests;

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
                'Authorization' => 'Bearer '.$this->login(),
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
                'Authorization' => 'Bearer '.$this->login(),
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
}
