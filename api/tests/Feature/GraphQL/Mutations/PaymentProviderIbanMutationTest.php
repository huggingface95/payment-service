<?php

namespace Feature\GraphQL\Mutations;

use App\Models\PaymentProviderIban;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentProviderIbanMutationTest extends TestCase
{
    /**
     * Regions Mutation Testing
     *
     * @return void
     */
    public function testCreatePaymentProviderIbanNoAuth(): void
    {
        $seq = DB::table('payment_provider_ibans')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_provider_ibans_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreatePaymentProviderIban(
                $name: String!,
                $company_id: ID!,
                $currency_id: ID!
            ) {
                createPaymentProviderIban(input: {
                    name: $name,
                    company_id: $company_id,
                    currency_id: $currency_id,
                    is_active: true
                }) {
                    id
                    name
                    company {
                        id
                        name
                    }
                    currency {
                        id
                        name
                    }
                    is_active
                }
            }
        ', [
            'name' => 'New PaymentProviderIban',
            'company_id' => 1,
            'currency_id' => 1,
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreatePaymentProviderIban(): void
    {
        $seq = DB::table('payment_provider_ibans')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_provider_ibans_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(['query' => 'mutation CreatePaymentProviderIban(
                $name: String!,
                $company_id: ID!,
                $currency_id: ID!,
                $logo_id: ID,
                $swift: String,
                $sort_code: String,
                $provider_address: String,
                $about: String
            ) {
                createPaymentProviderIban(input: {
                    name: $name,
                    company_id: $company_id,
                    currency_id: $currency_id,
                    logo_id: $logo_id,
                    swift: $swift
                    sort_code: $sort_code
                    provider_address: $provider_address
                    about: $about
                    is_active: true
                }) {
                    id
                    name
                    company {
                        id
                        name
                    }
                    currency {
                        id
                        name
                    }
                    logo {
                        id
                        file_name
                    }
                    swift
                    sort_code
                    provider_address
                    about
                    is_active
                }
            }',
            'variables' => [
                'name' => 'New PaymentProviderIban',
                'company_id' => 1,
                'currency_id' => 1,
                'swift' => 'FHJFJHFJFHJ6787',
                'sort_code' => '65433322',
                'provider_address' => 'Provider Address',
                'about' => 'About provider',
            ],
        ],
        [
            'Authorization' => 'Bearer '.$this->login(),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createPaymentProviderIban']['id'],
                'name' => $id['data']['createPaymentProviderIban']['name'],
                'swift' => $id['data']['createPaymentProviderIban']['swift'],
                'sort_code' => $id['data']['createPaymentProviderIban']['sort_code'],
                'provider_address' => $id['data']['createPaymentProviderIban']['provider_address'],
                'about' => $id['data']['createPaymentProviderIban']['about'],
                'is_active' => $id['data']['createPaymentProviderIban']['is_active'],
                'company' => $id['data']['createPaymentProviderIban']['company'],
                'currency' => $id['data']['createPaymentProviderIban']['currency'],
                'logo' => $id['data']['createPaymentProviderIban']['logo'],
            ],
        ]);
    }

    public function testUpdatePaymentProviderIban(): void
    {
        $paymentProviderIban = PaymentProviderIban::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(['query' => 'mutation UpdatePaymentProviderIban(
                $id: ID!,
                $name: String!,
                $company_id: ID!,
                $currency_id: ID!
                $logo_id: ID
            ) {
                updatePaymentProviderIban(id: $id, input: {
                    name: $name,
                    company_id: $company_id,
                    currency_id: $currency_id,
                    logo_id: $logo_id,
                    is_active: true
                }) {
                    id
                    name
                    company {
                        id
                        name
                    }
                    currency {
                        id
                        name
                    }
                    logo {
                        id
                        file_name
                    }
                    is_active
                }
            }',
            'variables' => [
                'id' => $paymentProviderIban->id,
                'name' => 'New PaymentProviderIban updated',
                'company_id' => 1,
                'currency_id' => 1,
                'logo_id' => 1,
            ],
        ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['updatePaymentProviderIban']['id'],
                'name' => $id['data']['updatePaymentProviderIban']['name'],
                'is_active' => $id['data']['updatePaymentProviderIban']['is_active'],
                'company' => $id['data']['updatePaymentProviderIban']['company'],
                'currency' => $id['data']['updatePaymentProviderIban']['currency'],
                'logo' => $id['data']['updatePaymentProviderIban']['logo'],
            ],
        ]);
    }

    public function testDeletePaymentProviderIban(): void
    {
        $paymentProviderIban = PaymentProviderIban::orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(['query' => 'mutation DeletePaymentProviderIban(
                $id: ID!
            ) {
                deletePaymentProviderIban(id: $id) {
                    id
                    name
                    company {
                        id
                        name
                    }
                    currency {
                        id
                        name
                    }
                    is_active
                }
            }',
            'variables' => [
                'id' => $paymentProviderIban->id,
                'name' => 'New PaymentProviderIban updated',
                'company_id' => 1,
                'currency_id' => 1,
            ],
        ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deletePaymentProviderIban']['id'],
                'name' => $id['data']['deletePaymentProviderIban']['name'],
                'is_active' => $id['data']['deletePaymentProviderIban']['is_active'],
                'company' => $id['data']['deletePaymentProviderIban']['company'],
                'currency' => $id['data']['deletePaymentProviderIban']['currency'],
            ],
        ]);
    }
}
