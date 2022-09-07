<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class PaymentProviderQueryTest extends TestCase
{
    /**
     * PaymentProvider Query Testing
     *
     * @return void
     */

    public function testQueryPaymentProvider(): void
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

    public function testQueryPaymentProvidersList(): void
    {
        $this->login();

        $payment_provider = DB::connection('pgsql_test')->table('payment_provider')->select('*')->orderBy('id', 'ASC')->get();

        $this->graphQL('
        query {
            paymentProviders (orderBy: { column: ID, order: ASC }) {
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

    public function testQueryPaymentProviderOrderBy(): void
    {
        $this->login();

        $payment_provider = DB::connection('pgsql_test')->table('payment_provider')->select('*')->orderBy('id', 'ASC')->get();

        $this->graphQL('
        query {
            paymentProviders(orderBy: { column: ID, order: ASC }) {
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
}
