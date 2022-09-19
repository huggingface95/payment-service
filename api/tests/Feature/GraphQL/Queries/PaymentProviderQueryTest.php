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

        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->orderBy('id')
            ->latest('id')
            ->first();

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

        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();

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

        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();

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

    public function testQueryPaymentProviderByName(): void
    {
        $this->login();

        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $this->graphQL('
        query PaymentProviders($name: Mixed) {
            paymentProviders(
                filter: {
                    column: NAME
                    operator: ILIKE
                    value: $name
                }
            ) {
                data {
                    id
                    name
                    description
                }
            }
        }
        ', [
            "name" => (string) $payment_provider->name
        ])->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }

    public function testQueryPaymentProviderByPaymentSystem(): void
    {
        $this->login();

        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_provider_payment_system')
            ->first();

        $this->graphQL('
        query PaymentProviders($id: Mixed) {
            paymentProviders(
                filter: {
                    column: HAS_PAYMENT_SYSTEMS_FILTER_BY_ID
                    value: $id
                }
            ) {
                data {
                    id
                    name
                    description
                }
            }
        }
        ', [
            "id" => (string) $payment_system->payment_system_id
        ])->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }

    public function testQueryPaymentProviderByCompanyId(): void
    {
        $this->login();

        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $this->graphQL('
        query PaymentProviders($id: Mixed) {
            paymentProviders(
                filter: {
                    column: HAS_PAYMENT_SYSTEMS_FILTER_BY_ID
                    value: $id
                }
            ) {
                data {
                    id
                    name
                    description
                }
            }
        }
        ', [
            "id" => (string) $payment_provider->company_id
        ])->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }
}
