<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Accounts;

class PaymentProviderTest extends TestCase
{
    /**
     * PaymentProvider Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreatePaymentProvider()
    {
        $this->login();
        $this->graphQL('
            mutation CreatePaymentProvider(
                $name: String!
                $description: String
            )
            {
                createPaymentProvider (
                    name: $name
                    description: $description
                    is_active: true
                )
                {
                    id
                }
            }
        ', [
            'name' =>  'PaymentProvider_'.\Illuminate\Support\Str::random(3),
            'description' => 'ProviderDesc_'.\Illuminate\Support\Str::random(3),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createPaymentProvider' => [
                    'id' => $id['data']['createPaymentProvider']['id']
                ],
            ],
        ]);
    }

    public function testUpdatePaymentProvider()
    {
        $this->login();
        $payment_provider = \App\Models\PaymentProvider::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation UpdatePaymentProvider(
                $id: ID!
                $name: String
                $description: String
            )
            {
                updatePaymentProvider (
                    id: $id
                    name: $name
                    description: $description
                )
                {
                    id
                    name
                }
            }
        ', [
            'id' => strval($payment_provider[0]->id),
            'name' => 'PaymentProviderName_Updated_'.\Illuminate\Support\Str::random(3),
            'description' => 'PaymentProviderDescription_Updated_'.\Illuminate\Support\Str::random(3)
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updatePaymentProvider' => [
                    'id' => $id['data']['updatePaymentProvider']['id'],
                    'name' => $id['data']['updatePaymentProvider']['name'],
                ],
            ],
        ]);
    }

    public function testQueryPaymentProvider()
    {
        $this->login();
        $payment_provider = \App\Models\PaymentProvider::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            query PaymentProvider($id:ID!){
                paymentProvider(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($payment_provider[0]->id)
        ])->seeJson([
                'data' => [
                    'paymentProvider' => [
                        'id' => strval($payment_provider[0]->id),
                    ],
                ],
        ]);
    }

    public function testQueryPaymentProviderOrderBy()
    {
        $this->login();
        $payment_provider = \App\Models\PaymentProvider::orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            paymentProviders(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
                [
                    'id' => strval($payment_provider[0]->id)
                ]
        ]);
    }

    public function testQueryPaymentProviderWhere()
    {
        $this->login();
        $payment_provider = \App\Models\PaymentProvider::where('id', 1)->get();
        $this->graphQL('
        query {
            paymentProviders(where: { column: ID, value: 1}) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($payment_provider[0]->id)
            ]
        ]);
    }

    public function testDeletePaymentProvider()
    {
        $this->login();
        $payment_provider = \App\Models\PaymentProvider::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation DeletePaymentProvider(
                $id: ID!
            )
            {
                deletePaymentProvider (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($payment_provider[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deletePaymentProvider' => [
                    'id' => $id['data']['deletePaymentProvider']['id']
                ],
            ],
        ]);
    }

}

