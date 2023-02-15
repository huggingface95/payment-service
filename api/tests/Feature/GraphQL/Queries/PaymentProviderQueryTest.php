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
    public function testQueryPaymentProviderNoAuth(): void
    {
        $this->graphQL('
            {
                paymentProviders {
                    data {
                         id
                        name
                        description
                        is_active
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPaymentProvider(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->orderBy('id', 'ASC')
            ->first();

        $paymentSystems = DB::connection('pgsql_test')
            ->table('payment_system')
            ->select('name')
            ->where('payment_provider_id', $payment_provider->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentProvider($id:ID!){
                    paymentProvider(id: $id) {
                        id
                        payment_systems {
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $payment_provider->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'data' => [
                'paymentProvider' => [
                    'id' => (string) $payment_provider->id,
                    'payment_systems' => [[
                        'name' => $paymentSystems->name,
                    ]],
                ],
            ],
        ]);
    }

    public function testQueryPaymentProvidersList(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query {
                    paymentProviders (orderBy: { column: ID, order: ASC }) {
                        data {
                          id
                          name
                          description
                          is_active
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_provider[0]->id,
                'name' => (string) $payment_provider[0]->name,
                'description' => (string) $payment_provider[0]->description,
                'is_active' => $payment_provider[0]->is_active,
            ],
        ]);
    }

    public function testQueryPaymentProviderFilterByName(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'name' => (string) $payment_provider->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }

    public function testQueryPaymentProviderFilterByPaymentSystem(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $payment_system = DB::connection('pgsql_test')
            ->table('payment_system')
            ->where('payment_provider_id', $payment_provider->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => (string) $payment_system->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }

    public function testQueryPaymentProviderFilterByAccountId(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $account = DB::connection('pgsql_test')
            ->table('accounts')
            ->where('payment_provider_id', $payment_provider->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentProviders($id: Mixed) {
                    paymentProviders(
                        filter: {
                            column: HAS_ACCOUNT_FILTER_BY_ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $account->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }

    public function testQueryPaymentProviderFilterByCommissionTemplateId(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->where('payment_provider_id', $payment_provider->id)
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentProviders($id: Mixed) {
                    paymentProviders(
                        filter: {
                            column: HAS_COMMISSION_TEMPLATE_FILTER_BY_ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $commissionTemplate->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }

    public function testQueryPaymentProviderFilterByCompanyId(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentProviders($id: Mixed) {
                    paymentProviders(
                        filter: {
                            column: COMPANY_ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $payment_provider->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }

    public function testQueryPaymentProviderFilterById(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentProviders($id: Mixed) {
                    paymentProviders(
                        filter: {
                            column: ID
                            value: $id
                        }
                    ) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $payment_provider->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $payment_provider->id,
                'name' => (string) $payment_provider->name,
                'description' => (string) $payment_provider->description,
            ],
        ]);
    }
}
