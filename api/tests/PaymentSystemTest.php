<?php

use Illuminate\Support\Facades\DB;

class PaymentSystemTest extends TestCase
{
    /**
     * PaymentSystem Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreatePaymentSystem()
    {
        $this->login();
        $seq = DB::table('payment_system')->max('id') + 1;
        DB::select('ALTER SEQUENCE payment_system_id_seq RESTART WITH '.$seq);
        $this->graphQL('
            mutation CreatePaymentSystem(
                $name: String!
            )
            {
                createPaymentSystem (
                    name: $name
		            is_active: true
                    regions: {sync:1}
                    currencies: {sync:1}
                    providers: {sync:1}
                )
                {
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

    public function testUpdatePaymentSystem()
    {
        $this->login();
        $payment_system = DB::connection('pgsql_test')->table('payment_system')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation UpdatePaymentSystem(
                $id: ID!
                $name: String
            )
            {
                updatePaymentSystem (
                    id: $id
                    name: $name
                )
                {
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

    public function testQueryPaymentSystem()
    {
        $this->login();
        $payment_system = DB::connection('pgsql_test')->table('payment_system')->orderBy('id')->latest('id')->first();
        $this->graphQL('
            query PaymentSystem($id:ID!){
                paymentSystem(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($payment_system->id),
        ])->seeJson([
            'data' => [
                'paymentSystem' => [
                    'id' => strval($payment_system->id),
                ],
            ],
        ]);
    }

    public function testQueryPaymentSystemsList()
    {
        $this->login();
        $payment_system = DB::connection('pgsql_test')->table('payment_system')->select('*')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            paymentSystems (orderBy: { column: ID, order: DESC }) {
                data {
                  id
                  name
                  is_active
                }
            }
        }')->seeJsonContains([
            [
                'id' => strval($payment_system[0]->id),
                'name' => strval($payment_system[0]->name),
                'is_active' => $payment_system[0]->is_active,
            ],
        ]);
    }

    public function testQueryPaymentSystemsFilterById()
    {
        $this->login();
        $payment_system = DB::connection('pgsql_test')->table('payment_system')->select('*')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            paymentSystems (filter:{column:ID, value:11}) {
                data {
                  id
                  name
                  is_active
                }
            }
        }')->seeJsonContains([
            [
                'id' => strval($payment_system[0]->id),
                'name' => strval($payment_system[0]->name),
                'is_active' => $payment_system[0]->is_active,
            ],
        ]);
    }

    public function testQueryPaymentSystemsFilterHasProvider()
    {
        $this->login();
        $payment_system = DB::connection('pgsql_test')->table('payment_system')->select('*')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            paymentSystems (filter:{column:HAS_PROVIDERS_FILTER_BY_ID, value:1}) {
                data {
                  id
                  name
                  is_active
                }
            }
        }')->seeJsonContains([
            [
                'id' => strval($payment_system[0]->id),
                'name' => strval($payment_system[0]->name),
                'is_active' => $payment_system[0]->is_active,
            ],
        ]);
    }

    public function testQueryPaymentSystemsFilterHasCompany()
    {
        $this->login();
        $payment_system = DB::connection('pgsql_test')->table('payment_system')->select('*')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            paymentSystems (filter:{column:HAS_COMPANIES_FILTER_BY_ID, value:1}) {
                data {
                  id
                  name
                  is_active
                }
            }
        }')->seeJsonContains([
            [
                'id' => strval($payment_system[0]->id),
                'name' => strval($payment_system[0]->name),
                'is_active' => $payment_system[0]->is_active,
            ],
        ]);
    }

    public function testDeletePaymentSystem()
    {
        $this->login();
        $payment_system = DB::connection('pgsql_test')->table('payment_system')->orderBy('id', 'DESC')->get();
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
