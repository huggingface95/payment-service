<?php

namespace Feature\GraphQL\Queries;

use App\Models\BankCorrespondent;
use Tests\TestCase;

class BankCorrespondentsQueryTest extends TestCase
{
    public function testQueryBankCorrespondentsNoAuth(): void
    {
        $this->graphQL('
            {
                bankCorrespondents {
                    data {
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
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryBankCorrespondent(): void
    {
        $bankCorrespondent = BankCorrespondent::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query BankCorrespondent($id: ID!) {
                    bankCorrespondent(id: $id) {
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
                    'id' => $bankCorrespondent->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'bankCorrespondent' => [
                    'id' => (string) $bankCorrespondent->id,
                    'name' => (string) $bankCorrespondent->name,
                    'address' => (string) $bankCorrespondent->address,
                    'bank_code' => (string) $bankCorrespondent->bank_code,
                    'swift' => (string) $bankCorrespondent->swift,
                    'account_number' => (string) $bankCorrespondent->account_number,
                    'ncs_number' => (string) $bankCorrespondent->ncs_number,
                    'bank_account' => (string) $bankCorrespondent->bank_account,
                ],
            ],
        ]);
    }

    public function testQueryBankCorrespondentsList(): void
    {
        $bankCorrespondents = BankCorrespondent::get();

        foreach ($bankCorrespondents as $bankCorrespondent) {
            $data[] = [
                'id' => (string) $bankCorrespondent->id,
                'name' => (string) $bankCorrespondent->name,
                'address' => (string) $bankCorrespondent->address,
                'bank_code' => (string) $bankCorrespondent->bank_code,
                'swift' => (string) $bankCorrespondent->swift,
                'account_number' => (string) $bankCorrespondent->account_number,
                'ncs_number' => (string) $bankCorrespondent->ncs_number,
                'bank_account' => (string) $bankCorrespondent->bank_account,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    bankCorrespondents {
                        data {
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
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'bankCorrespondents' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryBankCorrespondentsWithFilterByName(): void
    {
        $bankCorrespondent = BankCorrespondent::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $bankCorrespondent->id,
            'name' => (string) $bankCorrespondent->name,
            'address' => (string) $bankCorrespondent->address,
            'bank_code' => (string) $bankCorrespondent->bank_code,
            'swift' => (string) $bankCorrespondent->swift,
            'account_number' => (string) $bankCorrespondent->account_number,
            'ncs_number' => (string) $bankCorrespondent->ncs_number,
            'bank_account' => (string) $bankCorrespondent->bank_account,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BankCorrespondents($name: Mixed) {
                    bankCorrespondents (
                        filter: { column: NAME, operator: ILIKE, value: $name }
                    ) {
                        data {
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
                }',
                'variables' => [
                    'name' => (string) $bankCorrespondent->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryBankCorrespondentsWithFilterByCurrencyId(): void
    {
        $this->markTestSkipped('Skipped');
        $bankCorrespondent = BankCorrespondent::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $bankCorrespondent->id,
            'name' => (string) $bankCorrespondent->name,
            'address' => (string) $bankCorrespondent->address,
            'bank_code' => (string) $bankCorrespondent->bank_code,
            'swift' => (string) $bankCorrespondent->swift,
            'account_number' => (string) $bankCorrespondent->account_number,
            'ncs_number' => (string) $bankCorrespondent->ncs_number,
            'bank_account' => (string) $bankCorrespondent->bank_account,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BankCorrespondents($id: Mixed) {
                    bankCorrespondents (
                        filter: { column: HAS_CURRENCIES_REGIONS_FILTER_BY_CURRENCY_ID, value: $id }
                    ) {
                        data {
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
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryBankCorrespondentsWithFilterByRegionId(): void
    {
        $this->markTestSkipped('Skipped');
        $bankCorrespondent = BankCorrespondent::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $bankCorrespondent->id,
            'name' => (string) $bankCorrespondent->name,
            'address' => (string) $bankCorrespondent->address,
            'bank_code' => (string) $bankCorrespondent->bank_code,
            'swift' => (string) $bankCorrespondent->swift,
            'account_number' => (string) $bankCorrespondent->account_number,
            'ncs_number' => (string) $bankCorrespondent->ncs_number,
            'bank_account' => (string) $bankCorrespondent->bank_account,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BankCorrespondents($id: Mixed) {
                    bankCorrespondents (
                        filter: { column: HAS_CURRENCIES_REGIONS_FILTER_BY_REGION_ID, value: $id }
                    ) {
                        data {
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
                }',
                'variables' => [
                    'id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryBankCorrespondentsWithFilterById(): void
    {
        $bankCorrespondent = BankCorrespondent::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $bankCorrespondent->id,
            'name' => (string) $bankCorrespondent->name,
            'address' => (string) $bankCorrespondent->address,
            'bank_code' => (string) $bankCorrespondent->bank_code,
            'swift' => (string) $bankCorrespondent->swift,
            'account_number' => (string) $bankCorrespondent->account_number,
            'ncs_number' => (string) $bankCorrespondent->ncs_number,
            'bank_account' => (string) $bankCorrespondent->bank_account,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BankCorrespondents($id: Mixed) {
                    bankCorrespondents (
                        filter: { column: ID, value: $id }
                    ) {
                        data {
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
                }',
                'variables' => [
                    'id' => $bankCorrespondent->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryBankCorrespondentsWithFilterByPaymentSystemId(): void
    {
        $bankCorrespondent = BankCorrespondent::orderBy('id', 'ASC')
            ->first();

        $data = [
            'id' => (string) $bankCorrespondent->id,
            'name' => (string) $bankCorrespondent->name,
            'address' => (string) $bankCorrespondent->address,
            'bank_code' => (string) $bankCorrespondent->bank_code,
            'swift' => (string) $bankCorrespondent->swift,
            'account_number' => (string) $bankCorrespondent->account_number,
            'ncs_number' => (string) $bankCorrespondent->ncs_number,
            'bank_account' => (string) $bankCorrespondent->bank_account,
        ];

        $this->postGraphQL(
            [
                'query' => 'query BankCorrespondents($id: Mixed) {
                    bankCorrespondents (
                        filter: { column: PAYMENT_SYSTEM_ID, value: $id }
                    ) {
                        data {
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
                }',
                'variables' => [
                    'id' => $bankCorrespondent->payment_system_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }
}
