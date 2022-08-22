<?php

use Illuminate\Support\Facades\DB;

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
        $seq = DB::table('payment_provider')->max('id') + 1;
        DB::select('ALTER SEQUENCE payment_provider_id_seq RESTART WITH '.$seq);
        $this->graphQL('
            mutation CreatePaymentProvider(
                $name: String!
                $description: String
                $company_id: ID!
            )
            {
                createPaymentProvider (
                    name: $name
                    description: $description
                    company_id: $company_id
                )
                {
                    id
                }
            }
        ', [
            'name' =>  'PaymentProvider_'.\Illuminate\Support\Str::random(3),
            'description' => 'ProviderDesc_'.\Illuminate\Support\Str::random(3),
            'company_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createPaymentProvider' => [
                    'id' => $id['data']['createPaymentProvider']['id'],
                ],
            ],
        ]);
    }

    public function testUpdatePaymentProvider()
    {
        $this->login();
        $payment_provider = DB::connection('pgsql_test')->table('payment_provider')->orderBy('id', 'DESC')->get();
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
            'description' => 'PaymentProviderDescription_Updated_'.\Illuminate\Support\Str::random(3),
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
        $payment_provider = DB::connection('pgsql_test')->table('payment_provider')->orderBy('id')->latest('id')->first();
        $this->graphQL('
            query PaymentProvider($id:ID!){
                paymentProvider(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($payment_provider->id),
        ])->seeJson([
            'data' => [
                'paymentProvider' => [
                    'id' => strval($payment_provider->id),
                ],
            ],
        ]);
    }

    public function testQueryPaymentProvidersList()
    {
        $this->login();
        $payment_provider = DB::connection('pgsql_test')->table('payment_provider')->select('*')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            paymentProviders (orderBy: { column: ID, order: DESC }) {
                data {
                  id
                  name
                  description
                  is_active
                }
            }
        }')->seeJsonContains([
            [
                'id' => strval($payment_provider[0]->id),
                'name' => strval($payment_provider[0]->name),
                'description' => strval($payment_provider[0]->description),
                'is_active' => $payment_provider[0]->is_active,
            ],
        ]);
    }

    public function testQueryPaymentProviderOrderBy()
    {
        $this->login();
        $payment_provider = DB::connection('pgsql_test')->table('payment_provider')->select('*')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            paymentProviders(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($payment_provider[0]->id),
            ],
        ]);
    }

    public function testDeletePaymentProvider()
    {
        $this->login();
        $payment_provider = DB::connection('pgsql_test')->table('payment_provider')->orderBy('id', 'DESC')->get();
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
                    'id' => $id['data']['deletePaymentProvider']['id'],
                ],
            ],
        ]);
    }
}
