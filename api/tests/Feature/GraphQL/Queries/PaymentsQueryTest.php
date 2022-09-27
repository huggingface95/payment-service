<?php

namespace Tests;

use App\Models\Account;
use App\Models\Payments;

class PaymentsQueryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Account::where('id', 1)->update(['current_balance' => 100000, 'available_balance' => 100000]);
        Payments::factory()->count(3)->create();
    }

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

        $payment = Payments::first();

        $this->graphQL('
        query payment($id: ID!) {
            payment(id: $id) {
                id
                amount
            }
        }
        ', [
            'id' => $payment->id,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPaymentsList(): void
    {
        $this->login();

        $payments = Payments::get();

        $expect = [];
        foreach ($payments as $payment) {
            $expect['data']['payments']['data'][] = [
                'id' => (string) $payment['id'],
                'amount' => (int) $payment['amount'],
            ];
        }

        $this->graphQL('
        query {
            payments {
                data {
                    id
                    amount
                }
            }
        }
        ')->seeJson($expect);
    }

    public function testQueryPayment(): void
    {
        $this->login();
        
        $payment = Payments::first();

        $this->graphQL('
        query payment($id: ID!) {
            payment(id: $id) {
                id
                amount
            }
        }
        ', [
            'id' => $payment->id,
        ])->seeJson([
            'data' => [
                'payment' => [
                    'id' => (string) $payment->id,
                    'amount' => (int) $payment->amount,
                ],
            ],
        ]);
    }
}
