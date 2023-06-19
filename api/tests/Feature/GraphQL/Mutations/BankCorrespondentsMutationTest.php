<?php

namespace Feature\GraphQL\Mutations;

use App\Models\BankCorrespondent;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BankCorrespondentsMutationTest extends TestCase
{
    /**
     * Bank Correspondents Mutation Testing
     *
     * @return void
     */
    public function testCreateBankCorrespondentNoAuth(): void
    {
        $seq = DB::table('bank_correspondents')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE bank_correspondents_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateBankCorrespondent(
                $name: String!,
                $address: String!,
                $bank_code: String!,
                $bank_account: String!,
                $swift: String!,
                $account_number: String!,
                $ncs_number: String!,
                $payment_system_id: ID!,
                $country_id: ID!,
            ) {
                createBankCorrespondent(input: {
                    name: $name,
                    address: $address,
                    bank_code: $bank_code
                    bank_account: $bank_account
                    account_number: $account_number
                    ncs_number: $ncs_number,
                    payment_system_id: $payment_system_id,
                    currencies_and_regions: [{currency_id:[1], regions:[1]}]
                    country_id: $country_id
                    swift: $swift
                    is_active: true
                }) {
                    id
                    name
                    address
                    bank_code
                    swift
                    account_number
                    ncs_number
                    bank_account
                }
            }
        ', [
            'name' => 'New PaymentBank',
            'address' => 'New PaymentBank address',
            'bank_code' => '5656565465',
            'bank_account' => '5465456456sd',
            'account_number' => '858559696',
            'ncs_number' => '899989d89',
            'payment_system_id' => 1,
            'country_id' => 1,
            'swift' => 'IBXXAEWDZ1E',
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateBankCorrespondent(): void
    {
        $seq = DB::table('bank_correspondents')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE bank_correspondents_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(['query' => 'mutation CreateBankCorrespondent(
                $name: String!,
                $address: String!,
                $bank_code: String!,
                $bank_account: String!,
                $swift: String!,
                $account_number: String!,
                $ncs_number: String!,
                $payment_system_id: ID!,
                $country_id: ID!,
            ) {
                createBankCorrespondent(input: {
                    name: $name,
                    address: $address,
                    bank_code: $bank_code
                    bank_account: $bank_account
                    account_number: $account_number
                    ncs_number: $ncs_number,
                    payment_system_id: $payment_system_id,
                    currencies_and_regions: [{currency_id:[1], regions:[1]}]
                    country_id: $country_id
                    swift: $swift
                    is_active: true
                }) {
                    id
                    name
                    address
                    bank_code
                    swift
                    account_number
                    ncs_number
                    bank_account
                }
            }',
            'variables' => [
                'name' => 'New PaymentBank',
                'address' => 'New PaymentBank address',
                'bank_code' => '5656565465',
                'bank_account' => '5465456456sd',
                'account_number' => '858559696',
                'ncs_number' => '899989d89',
                'payment_system_id' => 1,
                'country_id' => 1,
                'swift' => 'IBXXAEWDZ1E',
            ],
        ],
        [
            'Authorization' => 'Bearer '.$this->login(),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createBankCorrespondent']['id'],
                'address' => $id['data']['createBankCorrespondent']['address'],
                'bank_code' => $id['data']['createBankCorrespondent']['bank_code'],
                'bank_account' => $id['data']['createBankCorrespondent']['bank_account'],
                'account_number' => $id['data']['createBankCorrespondent']['account_number'],
                'ncs_number' => $id['data']['createBankCorrespondent']['ncs_number'],
                'swift' => $id['data']['createBankCorrespondent']['swift'],
                'name' => $id['data']['createBankCorrespondent']['name'],
            ],
        ]);
    }

    public function testUpdateBankCorrespondent(): void
    {
        $this->postGraphQL(['query' => 'mutation UpdateBankCorrespondent(
                $id: ID!
                $name: String!,
                $address: String!,
                $bank_code: String!,
                $bank_account: String!,
                $swift: String!,
                $account_number: String!,
                $ncs_number: String!,
                $payment_system_id: ID!,
                $country_id: ID!,
            ) {
                updateBankCorrespondent(id: $id, input: {
                    name: $name,
                    address: $address,
                    bank_code: $bank_code
                    bank_account: $bank_account
                    account_number: $account_number
                    ncs_number: $ncs_number,
                    payment_system_id: $payment_system_id,
                    currencies_and_regions: [{currency_id:[1], regions:[1]}]
                    country_id: $country_id
                    swift: $swift
                    is_active: true
                }) {
                    id
                    name
                    address
                    bank_code
                    swift
                    account_number
                    ncs_number
                    bank_account
                }
            }',
            'variables' => [
                'id' => 10,
                'name' => 'New PaymentBank',
                'address' => 'New PaymentBank address',
                'bank_code' => '5656565465',
                'bank_account' => '5465456456sd',
                'account_number' => '858559696',
                'ncs_number' => '899989d89',
                'payment_system_id' => 1,
                'country_id' => 1,
                'swift' => 'IBXXAEWDZ1E',
            ],
        ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['updateBankCorrespondent']['id'],
                'address' => $id['data']['updateBankCorrespondent']['address'],
                'bank_code' => $id['data']['updateBankCorrespondent']['bank_code'],
                'bank_account' => $id['data']['updateBankCorrespondent']['bank_account'],
                'account_number' => $id['data']['updateBankCorrespondent']['account_number'],
                'ncs_number' => $id['data']['updateBankCorrespondent']['ncs_number'],
                'swift' => $id['data']['updateBankCorrespondent']['swift'],
                'name' => $id['data']['updateBankCorrespondent']['name'],
            ],
        ]);
    }

    public function testDeleteBankCorrespondent(): void
    {
        $bankCorrespondent = BankCorrespondent::orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteBankCorrespondent($id: ID!) {
                    deleteBankCorrespondent(id: $id) {
                        id
                        name
                        address
                        bank_code
                        swift
                        account_number
                        ncs_number
                        bank_account
                    }
                }',
                'variables' => [
                    'id' => (string) $bankCorrespondent[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteBankCorrespondent']['id'],
                'address' => $id['data']['deleteBankCorrespondent']['address'],
                'bank_code' => $id['data']['deleteBankCorrespondent']['bank_code'],
                'bank_account' => $id['data']['deleteBankCorrespondent']['bank_account'],
                'account_number' => $id['data']['deleteBankCorrespondent']['account_number'],
                'ncs_number' => $id['data']['deleteBankCorrespondent']['ncs_number'],
                'swift' => $id['data']['deleteBankCorrespondent']['swift'],
                'name' => $id['data']['deleteBankCorrespondent']['name'],
            ],
        ]);
    }
}
