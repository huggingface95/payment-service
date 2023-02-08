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
    public function testQueryPaymentSystemNoAuth(): void
    {
        $this->graphQL('
            {
                paymentSystems {
                    data {
                         id
                        name
                        is_active
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPaymentSystem(): void
    {
        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentSystem($id:ID!){
                    paymentSystem(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $payment_system->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'paymentSystem' => [
                    'id' => (string) $payment_system->id,
                ],
            ],
        ]);
    }

    public function testQueryPaymentSystemsList(): void
    {
        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query {
                    paymentSystems (orderBy: { column: ID, order: DESC }) {
                        data {
                          id
                          name
                          is_active
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_system[0]->id,
                'name' => (string) $payment_system[0]->name,
                'is_active' => $payment_system[0]->is_active,
            ],
        ]);
    }

    public function testQueryPaymentSystemsFilterById(): void
    {
        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentSystems ($id: Mixed) {
                    paymentSystems (filter: {column: ID, value: $id}) {
                        data {
                          id
                          name
                          is_active
                        }
                    }
                }',
                'variables' => [
                    'id' => $payment_system[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_system[0]->id,
                'name' => (string) $payment_system[0]->name,
                'is_active' => $payment_system[0]->is_active,
            ],
        ]);
    }

    public function testQueryPaymentSystemsFilterHasProvider(): void
    {
        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentSystems($id: Mixed) {
                    paymentSystems(filter: { column: HAS_PROVIDERS_FILTER_BY_ID, value: $id }) {
                        data {
                            id
                            name
                            is_active
                        }
                    }
                }',
                'variables' => [
                    'id' => $payment_system->payment_provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_system->id,
                'name' => (string) $payment_system->name,
                'is_active' => $payment_system->is_active,
            ],
        ]);
    }

    public function testQueryPaymentSystemsFilterHasCompany(): void
    {
        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                {
                    paymentSystems (filter: { column: HAS_COMPANIES_FILTER_BY_ID, value: 1 }) {
                        data {
                          id
                          name
                          is_active
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_system[0]->id,
                'name' => (string) $payment_system[0]->name,
                'is_active' => $payment_system[0]->is_active,
            ],
        ]);
    }
}
