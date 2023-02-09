<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class PaymentSystemMutationTest extends TestCase
{
    /**
     * PaymentSystem Mutation Testing
     *
     * @return void
     */
    public function testCreatePaymentSystemNoAuth(): void
    {
        $seq = DB::table('payment_system')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_system_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreatePaymentSystem($name: String!) {
                createPaymentSystem(
                    input: {
                        name: $name
                        is_active: true
                        regions: { sync: 1 }
                        currencies: { sync: 1 }
                        payment_provider_id: 1
                    }
                ) {
                    id
                }
            }
        ', [
            'name' =>  'PaymentSystem_'.\Illuminate\Support\Str::random(3),
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreatePaymentSystem(): void
    {
        $seq = DB::table('payment_system')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_system_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreatePaymentSystem($name: String!) {
                    createPaymentSystem(
                        input: {
                            name: $name
                            is_active: true
                            regions: { sync: 1 }
                            currencies: { sync: 1 }
                            payment_provider_id: 1
                        }
                    ) {
                        id
                    }
                }',
                'variables' => [
                    'name' =>  'PaymentSystem_'.\Illuminate\Support\Str::random(3),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createPaymentSystem' => [
                    'id' => $id['data']['createPaymentSystem']['id'],
                ],
            ],
        ]);
    }

    public function testUpdatePaymentSystem(): void
    {
        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdatePaymentSystem($id: ID!, $name: String!) {
                    updatePaymentSystem(id: $id, input: { name: $name, is_active: false }) {
                        id
                        name
                    }
                }',
                'variables' => [
                    'id' => (string) $payment_system[0]->id,
                    'name' => 'PaymentSystem_Updated_'.\Illuminate\Support\Str::random(3),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updatePaymentSystem' => [
                    'id' => $id['data']['updatePaymentSystem']['id'],
                    'name' => $id['data']['updatePaymentSystem']['name'],
                ],
            ],
        ]);
    }

    public function testDeletePaymentSystem(): void
    {
        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeletePaymentSystem(
                    $id: ID!
                )
                {
                    deletePaymentSystem (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $payment_system[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deletePaymentSystem' => [
                    'id' => $id['data']['deletePaymentSystem']['id'],
                ],
            ],
        ]);
    }
}
