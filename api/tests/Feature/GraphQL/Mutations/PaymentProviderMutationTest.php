<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class PaymentProviderMutationTest extends TestCase
{
    /**
     * PaymentProvider Mutation Testing
     *
     * @return void
     */

    public function testCreatePaymentProviderNoAuth(): void
    {
        $seq = DB::table('payment_provider')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_provider_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreatePaymentProvider(
                $name: String!
                $description: String
                $company_id: ID!
            )
            {
                createPaymentProvider (
                    input: {
                        name: $name
                        description: $description
                        company_id: $company_id
                    }
                )
                {
                    id
                }
            }
        ', [
            'name' =>  'PaymentProvider_'.\Illuminate\Support\Str::random(3),
            'description' => 'ProviderDesc_'.\Illuminate\Support\Str::random(3),
            'company_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreatePaymentProvider(): void
    {
        $seq = DB::table('payment_provider')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_provider_id_seq RESTART WITH '.$seq);

        $this->postGraphQL([
            'query' => '
                mutation CreatePaymentProvider(
                    $name: String!
                    $description: String
                    $company_id: ID!
                )
                {
                    createPaymentProvider (
                        input: {
                            name: $name
                            description: $description
                            company_id: $company_id
                        }
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'name' =>  'PaymentProvider_'.\Illuminate\Support\Str::random(3),
                'description' => 'ProviderDesc_'.\Illuminate\Support\Str::random(3),
                'company_id' => 1,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
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

    public function testUpdatePaymentProvider(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation UpdatePaymentProvider(
                    $id: ID!
                    $name: String!
                    $description: String
                    $company_id: ID!
                )
                {
                    updatePaymentProvider (
                        id: $id
                        input: {
                            name: $name
                            description: $description
                            company_id: $company_id
                        }
                    )
                    {
                        id
                        name
                    }
                }',
            'variables' => [
                'id' => (string) $payment_provider[0]->id,
                'name' => 'PaymentProviderName_Updated_'.\Illuminate\Support\Str::random(3),
                'description' => 'PaymentProviderDescription_Updated_'.\Illuminate\Support\Str::random(3),
                'company_id' => '1',
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
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

    public function testDeletePaymentProvider(): void
    {
        $payment_provider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
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
                }',
            'variables' => [
                'id' => strval($payment_provider[0]->id),
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
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
