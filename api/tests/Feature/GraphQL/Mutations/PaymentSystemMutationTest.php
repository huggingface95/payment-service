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

    public function testCreatePaymentSystem(): void
    {
        $this->login();

        $seq = DB::table('payment_system')->max('id') + 1;
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
        ]);

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
        $this->login();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            mutation UpdatePaymentSystem($id: ID!, $name: String!) {
                updatePaymentSystem(id: $id, input: { name: $name, is_active: false }) {
                    id
                    name
                }
            }
        ', [
            'id' => strval($payment_system[0]->id),
            'name' => 'PaymentSystem_Updated_'.\Illuminate\Support\Str::random(3),
        ]);

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
        $this->login();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
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
            }
        ', [
            'id' => strval($payment_system[0]->id),
        ]);

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
