<?php

namespace Feature\GraphQL\Mutations;

use App\Models\PaymentBank;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentBanksMutationTest extends TestCase
{
    /**
     * Regions Mutation Testing
     *
     * @return void
     */
    public function testCreatePaymentBankNoAuth(): void
    {
        $seq = DB::table('payment_banks')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_banks_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreatePaymentBank(
                $name: String!,
                $address: String!,
                $bank_code: String!,
                $payment_system_code: String!,
                $payment_system_id: ID!,
                $payment_provider_id: ID!,
                $country_id: ID!,
                $swift: String,
                $account_number: String!,
                $ncs_number: String!

            ) {
                createPaymentBank(input: {
                    name: $name,
                    address: $address,
                    payment_system_id: $payment_system_id,
                    payment_provider_id: $payment_provider_id,
                    bank_code: $bank_code
                    payment_system_code: $payment_system_code
                    currencies_and_regions: [{currency_id:[1], regions:[1]}]
                    country_id: $country_id
                    swift: $swift
                    account_number: $account_number
                    ncs_number: $ncs_number
                }) {
                    id
                }
            }
        ', [
            'name' => 'New PaymentBank',
            'address' => 'New PaymentBank address',
            'bank_code' => '5656565465',
            'payment_system_id' => 1,
            'payment_system_code' =>'4564564654655',
            'payment_provider_id' => 1,
            'country_id' => 1,
            'swift' => "IBXXAEWDZ1E",
            'account_number' => '858559696',
            'ncs_number' => '899989d89',
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreatePaymentBank(): void
    {
        $seq = DB::table('payment_banks')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE payment_banks_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(['query' => 'mutation CreatePaymentBank(
                $name: String!,
                $address: String!,
                $bank_code: String!,
                $payment_system_code: String!,
                $payment_system_id: ID!,
                $payment_provider_id: ID!,
                $country_id: ID!,
                $swift: String,
                $account_number: String!,
                $ncs_number: String!
            ) {
                createPaymentBank(input: {
                    name: $name,
                    address: $address,
                    payment_system_id: $payment_system_id,
                    payment_provider_id: $payment_provider_id,
                    bank_code: $bank_code
                    payment_system_code: $payment_system_code
                    currencies_and_regions: [{currency_id:[1], regions:[1]}]
                    country_id: $country_id
                    swift: $swift
                    account_number: $account_number
                    ncs_number: $ncs_number
                }) {
                    id
                }
            }',
            'variables' => [
                'name' => 'New PaymentBank 2',
                'address' => 'New PaymentBank address 2',
                'bank_code' => '5656565464',
                'payment_system_id' => 3,
                'payment_system_code' =>'4564564654654',
                'payment_provider_id' => 11,
                'country_id' => 1,
                'swift' => "IBXXAEWDZ1E",
                'account_number' => '858559696',
                'ncs_number' => '899989d89',
            ],
        ],
        [
            'Authorization' => 'Bearer '.$this->login(),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createPaymentBank']['id'],
            ],
        ]);
    }

    public function testUpdatePaymentBank(): void
    {
        $this->postGraphQL(['query' => 'mutation UpdatedPaymentBank(
                $id: ID!,
                $name: String!,
                $address: String!,
                $bank_code: String!,
                $payment_system_code: String!,
                $payment_system_id: ID!,
                $payment_provider_id: ID!,
                $country_id: ID!,
                $swift: String,
                $account_number: String!,
                $ncs_number: String!
            ) {
                updatePaymentBank(id: $id, input: {
                    name: $name,
                    address: $address,
                    payment_system_id: $payment_system_id,
                    payment_provider_id: $payment_provider_id,
                    bank_code: $bank_code
                    payment_system_code: $payment_system_code
                    currencies_and_regions: [{currency_id:[1], regions:[1]}]
                    country_id: $country_id
                    swift: $swift
                    account_number: $account_number
                    ncs_number: $ncs_number
                }) {
                    id
                }
            }',
            'variables' => [
                'id' => 10,
                'name' => 'New PaymentBank 10',
                'address' => 'New PaymentBank address 10',
                'bank_code' => '5656565',
                'payment_system_id' => 10,
                'payment_system_code' =>'456456465',
                'payment_provider_id' => 10,
                'country_id' => 1,
                'swift' => "IBXXAEWDZ1E",
                'account_number' => '858559695',
                'ncs_number' => '899989d85',
            ],
        ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['updatePaymentBank']['id'],
            ],
        ]);
    }

    public function testDeletePaymentBank(): void
    {
        $paymentBank = PaymentBank::orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeletePaymentBank($id: ID!) {
                    deletePaymentBank(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $paymentBank[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deletePaymentBank']['id'],
            ],
        ]);
    }
}
