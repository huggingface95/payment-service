<?php

namespace Feature\GraphQL\Queries;

use App\Models\PaymentProviderIban;
use Tests\TestCase;

class PaymentProviderIbanQueryTest extends TestCase
{
    public function testQueryPaymentProviderIbanNoAuth(): void
    {
        $this->graphQL('
            {
                paymentProviderIbans {
                    data {
                        id
                        name
                        is_active
                        company {
                            id
                            name
                        }
                        currency {
                            id
                            name
                        }
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPaymentProviderIban(): void
    {
        $paymentProviderIban = PaymentProviderIban::orderBy('id', 'ASC')
            ->first();

        $company = $paymentProviderIban->company()->first();
        $currency = $paymentProviderIban->currency()->first();

        $this->postGraphQL(
            [
                'query' => '
                query PaymentProviderIban($id: ID!) {
                    paymentProviderIban(id: $id) {
                        id
                        name
                        is_active
                        company {
                            id
                            name
                        }
                        currency {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $paymentProviderIban->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'paymentProviderIban' => [
                    'id' => (string) $paymentProviderIban->id,
                    'name' => (string) $paymentProviderIban->name,
                    'is_active' => $paymentProviderIban->is_active,
                    'company' => [
                        'id' => (string) $company->id,
                        'name' => (string) $company->name,
                    ],
                    'currency' => [
                        'id' => (string) $currency->id,
                        'name' => (string) $currency->name,
                    ],
                ],
            ],
        ]);
    }

    public function testQueryPaymentProviderIbans(): void
    {
        $paymentProviderIban = PaymentProviderIban::orderBy('id', 'ASC')
            ->first();

        $company = $paymentProviderIban->company()->first();
        $currency = $paymentProviderIban->currency()->first();
        $logo = $paymentProviderIban->logo()->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    paymentProviderIbans (first: 1) {
                        data {
                            id
                            name
                            is_active
                            swift
                            sort_code
                            provider_address
                            about
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
                        }
                    }
                }',
                'variables' => [
                    'id' => $paymentProviderIban->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'paymentProviderIbans' => [
                    'data' => [[
                        'id' => (string) $paymentProviderIban->id,
                        'name' => (string) $paymentProviderIban->name,
                        'swift' => (string) $paymentProviderIban->swift,
                        'sort_code' => (string) $paymentProviderIban->sort_code,
                        'provider_address' => (string) $paymentProviderIban->provider_address,
                        'about' => (string) $paymentProviderIban->about,
                        'is_active' => $paymentProviderIban->is_active,
                        'company' => [
                            'id' => (string) $company->id,
                            'name' => (string) $company->name,
                        ],
                        'currency' => [
                            'id' => (string) $currency->id,
                            'name' => (string) $currency->name,
                        ],
                        'logo' => [
                            'id' => (string) $logo->id,
                            'file_name' => (string) $logo->file_name,
                        ]],
                    ],
                ],
            ],
        ]);
    }

    public function testQueryPaymentProviderIbansWithFilterByName(): void
    {
        $paymentProviderIban = PaymentProviderIban::orderBy('id', 'ASC')
            ->first();

        $company = $paymentProviderIban->company()->first();
        $currency = $paymentProviderIban->currency()->first();

        $this->postGraphQL(
            [
                'query' => '
                    query PaymentProviderIbans($name: Mixed) {
                    paymentProviderIbans (first: 1, filter:{
                        column: NAME, value:$name
                    }) {
                        data {
                            id
                            name
                            is_active
                            company {
                                id
                                name
                            }
                            currency {
                                id
                                name
                            }
                        }
                    }
                }',
                'variables' => [
                    'name' => $paymentProviderIban->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'paymentProviderIbans' => [
                    'data' => [[
                        'id' => (string) $paymentProviderIban->id,
                        'name' => (string) $paymentProviderIban->name,
                        'is_active' => $paymentProviderIban->is_active,
                        'company' => [
                            'id' => (string) $company->id,
                            'name' => (string) $company->name,
                        ],
                        'currency' => [
                            'id' => (string) $currency->id,
                            'name' => (string) $currency->name,
                        ]],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @dataProvider provide_testQueryPaymentProviderIbansWithFilterByCondition
     */
    public function testQueryPaymentProviderIbansWithFilterByCondition($cond, $value): void
    {
        $paymentProviderIbans = PaymentProviderIban::where($cond, $value)
            ->orderBy('id', 'ASC')
            ->get();

        $data = [
            'data' => [
                'paymentProviderIbans' => [],
            ],
        ];

        foreach ($paymentProviderIbans as $paymentProviderIban) {
            $data['data']['paymentProviderIbans']['data'][] = [
                'id' => (string) $paymentProviderIban->id,
                'name' => (string) $paymentProviderIban->name,
                'is_active' => $paymentProviderIban->is_active,
            ];
        }

        $this->postGraphQL(
            [
                'query' => 'query PaymentProviderIbans($id: Mixed) {
                    paymentProviderIbans (
                        filter: { column: '.strtoupper($cond).', operator: EQ, value: $id }
                    ) {
                        data {
                            id
                            name
                            is_active
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function provide_testQueryPaymentProviderIbansWithFilterByCondition()
    {
        return [
            ['company_id', '1'],
            ['currency_id', '1'],
        ];
    }
}
