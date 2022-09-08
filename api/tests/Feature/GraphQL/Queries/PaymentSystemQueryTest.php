<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class PaymentSystemQueryTest extends TestCase
{
    /**
     * PaymentSystem Query Testing
     *
     * @return void
     */

    public function testQueryPaymentSystem(): void
    {
        $this->login();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id')->
            latest('id')
            ->first();

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

    public function testQueryPaymentSystemsList(): void
    {
        $this->login();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->select('*')
            ->orderBy('id', 'DESC')
            ->get();

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

    public function testQueryPaymentSystemsFilterById(): void
    {
        $this->login();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();

        $this->graphQL('
        query {
            paymentSystems (filter:{column:ID, value:1}) {
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

    public function testQueryPaymentSystemsFilterHasProvider(): void
    {
        $this->login();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->select('*')
            ->first();

        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider_payment_system')
            ->select('*')
            ->where('payment_provider_id', $payment_system->id)
            ->first();

        $this->graphQL('
        query PaymentSystems($id: Mixed) {
            paymentSystems(filter: { column: HAS_PROVIDERS_FILTER_BY_ID, value: $id }) {
                data {
                    id
                    name
                    is_active
                }
            }
        }', [
                'id' => $payment_provider->payment_provider_id,
        ])->seeJsonContains([
            [
                'id' => strval($payment_system->id),
                'name' => strval($payment_system->name),
                'is_active' => $payment_system->is_active,
            ],
        ]);
    }

    public function testQueryPaymentSystemsFilterHasCompany(): void
    {
        $this->login();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();

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
}
