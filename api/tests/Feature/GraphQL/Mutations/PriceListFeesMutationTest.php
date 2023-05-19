<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\PriceListFee;
use Tests\TestCase;

class PriceListFeesMutationTest extends TestCase
{
    public function testCreatePriceListFeesNoAuth(): void
    {
        $data = [
            'name' => 'Test 1-'.time(),
            'type_id' => 1,
            'period_id' => 1,
            'operation_type_id' => 1,
            'price_list_id' => 1,
            'company_id' => 1,
        ];

        $this->graphQL('
        mutation (
            $name: String!
            $type_id: ID!
            $period_id: ID!
            $operation_type_id: ID!
            $price_list_id: ID!
            $company_id: ID!
        ) {
            createPriceListFees(
                input: {
                    name: $name
                    type_id: $type_id
                    period_id: $period_id
                    operation_type_id: $operation_type_id
                    price_list_id: $price_list_id
                    company_id: $company_id
                    fees: [
                        {
                            currency_id: 1
                            fee: []
                        }
                    ]
                }
            ) {
                id
            }
        }
        ', $data)->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreatePriceListFees(): void
    {
        $data = [
            'name' => 'Test 1-'.time(),
            'type_id' => 1,
            'period_id' => 1,
            'operation_type_id' => 1,
            'price_list_id' => 1,
            'fix' => 'Fix',
            'range' => 'Range',
            'percent' => 'Percent',
            'company_id' => 1,
        ];

        $fees = [
            [
                'currency_id' => '1',
                'fee' => [
                    ['mode' => 'Fix', 'fee' => 10, 'amount_from' => null, 'amount_to' => null, 'percent' => null],
                    ['mode' => 'Range', 'fee' => null, 'amount_from' => number_format(100, 5, '.', ''), 'amount_to' => number_format(300, 5, '.', ''), 'percent' => null],
                    ['mode' => 'Percent', 'fee' => null, 'amount_from' => null, 'amount_to' => null, 'percent' => 15],
                ],
            ],
            [
                'currency_id' => '2',
                'fee' => [
                    ['mode' => 'Fix', 'fee' => 5, 'amount_from' => null, 'amount_to' => null, 'percent' => null],
                    ['mode' => 'Range', 'fee' => null, 'amount_from' => number_format(20, 5, '.', ''), 'amount_to' => number_format(200, 5, '.', ''), 'percent' => null],
                    ['mode' => 'Percent', 'fee' => null, 'amount_from' => null, 'amount_to' => null, 'percent' => 10],
                ],
            ],
            [
                'currency_id' => '2',
                'fee' => [
                    ['mode' => 'Percent', 'fee' => null, 'amount_from' => null, 'amount_to' => null, 'percent' => 5],
                ],
            ],
        ];

        $this->postGraphQL(
            [
                'query' => '
            mutation (
                $name: String!
                $type_id: ID!
                $period_id: ID!
                $operation_type_id: ID!
                $price_list_id: ID!
                $fix: String!
                $range: String!
                $percent: String!
                $company_id: ID!
            ) {
                createPriceListFees(
                    input: {
                        name: $name
                        type_id: $type_id
                        period_id: $period_id
                        operation_type_id: $operation_type_id
                        price_list_id: $price_list_id
                        company_id: $company_id
                        fees: [
                            {
                                currency_id: 1
                                fee: [
                                    [
                                        { mode: $fix, fee: 10 }
                                        { mode: $range, amount_to: 300.00000, amount_from: 100.00000 }
                                        { mode: $percent, percent: 15 }
                                    ]
                                ]
                            },
                            {
                                currency_id: 2
                                fee: [
                                    [
                                        { mode: $fix, fee: 5 }
                                        { mode: $range, amount_to: 200.00000, amount_from: 20.00000 }
                                        { mode: $percent, percent: 10 }
                                    ],
                                    [
                                        { mode: $percent, percent: 5 }
                                    ]
                                ]
                            }
                        ]
                    }
                ) {
                    id
                    name
                    fees {
                        currency_id
                        fee {
                            mode
                            amount_from
                            amount_to
                            fee
                            percent
                        }
                    }
                }
            }',
                'variables' => $data,
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createPriceListFees' => [
                    'id' => $id['data']['createPriceListFees']['id'],
                    'name' => $data['name'],
                    'fees' => $fees,
                ],
            ],
        ]);
    }

    public function testUpdatePriceListFees(): void
    {
        $priceListFee = PriceListFee::first();

        $data = [
            'id' => $priceListFee->id,
            'name' => 'Test 2-'.time(),
            'type_id' => 2,
            'period_id' => 2,
            'operation_type_id' => 2,
            'price_list_id' => 1,
            'fix' => 'Fix',
            'range' => 'Range',
            'percent' => 'Percent',
            'company_id' => 1,
        ];

        $fees = [
            [
                'currency_id' => '1',
                'fee' => [
                    ['mode' => 'Fix', 'fee' => 13, 'amount_from' => null, 'amount_to' => null, 'percent' => null],
                    ['mode' => 'Range', 'fee' => null, 'amount_from' => number_format(250, 5, '.', ''), 'amount_to' => number_format(550, 5, '.', ''), 'percent' => null],
                    ['mode' => 'Percent', 'fee' => null, 'amount_from' => null, 'amount_to' => null, 'percent' => 17],
                ],
            ],
            [
                'currency_id' => '2',
                'fee' => [
                    ['mode' => 'Fix', 'fee' => 3, 'amount_from' => null, 'amount_to' => null, 'percent' => null],
                    ['mode' => 'Range', 'fee' => null, 'amount_from' => number_format(70, 5, '.', ''), 'amount_to' => number_format(700, 5, '.', ''), 'percent' => null],
                    ['mode' => 'Percent', 'fee' => null, 'amount_from' => null, 'amount_to' => null, 'percent' => 10],
                ],
            ],
            [
                'currency_id' => '2',
                'fee' => [
                    ['mode' => 'Percent', 'fee' => null, 'amount_from' => null, 'amount_to' => null, 'percent' => 7],
                ],
            ],
        ];

        $this->postGraphQL(
            [
                'query' => '
            mutation (
                $id: ID!
                $name: String!
                $type_id: ID!
                $period_id: ID!
                $operation_type_id: ID!
                $price_list_id: ID!
                $fix: String!
                $range: String!
                $percent: String!
                $company_id: ID!
            ) {
                updatePriceListFees(
                    id: $id
                    input: {
                        name: $name
                        type_id: $type_id
                        period_id: $period_id
                        operation_type_id: $operation_type_id
                        price_list_id: $price_list_id
                        company_id: $company_id
                        fees: [
                            {
                                currency_id: 1
                                fee: [
                                    [
                                        { mode: $fix, fee: 13 }
                                        { mode: $range, amount_to: 550.00000, amount_from: 250.00000 }
                                        { mode: $percent, percent: 17 }
                                    ]
                                ]
                            },
                            {
                                currency_id: 2
                                fee: [
                                    [
                                        { mode: $fix, fee: 3 }
                                        { mode: $range, amount_to: 700.00000, amount_from: 70.00000 }
                                        { mode: $percent, percent: 10 }
                                    ],
                                    [
                                        { mode: $percent, percent: 7 }
                                    ]
                                ]
                            }
                        ]
                    }
                ) {
                    id
                    name
                    fees {
                        currency_id
                        fee {
                            mode
                            amount_from
                            amount_to
                            fee
                            percent
                        }
                    }
                }
            }',
                'variables' => $data,
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $this->seeJson([
            'data' => [
                'updatePriceListFees' => [
                    'id' => (string) $priceListFee->id,
                    'name' => $data['name'],
                    'fees' => $fees,
                ],
            ],
        ]);
    }

    public function testCreatePriceListFeesValidationFail(): void
    {
        $data = [
            'name' => 'Test 1-'.time(),
            'type_id' => 1,
            'period_id' => 1,
            'operation_type_id' => 1,
            'price_list_id' => 1,
            'fix' => 'Fix',
            'range' => 'Range',
            'percent' => 'Percent',
            'company_id' => 1,
        ];

        $this->postGraphQL(
            [
                'query' => '
            mutation (
                $name: String!
                $type_id: ID!
                $period_id: ID!
                $operation_type_id: ID!
                $price_list_id: ID!
                $fix: String!
                $range: String!
                $percent: String!
                $company_id: ID!
            ) {
                createPriceListFees(
                    input: {
                        name: $name
                        type_id: $type_id
                        period_id: $period_id
                        operation_type_id: $operation_type_id
                        price_list_id: $price_list_id
                        company_id: $company_id
                        fees: [
                            {
                                currency_id: 1
                                fee: [
                                    [
                                        { mode: $fix, fee: 13 }
                                        { mode: $range, amount_to: 550, amount_from: 250 }
                                        { mode: $percent, percent: 17 }
                                    ],
                                    [
                                        { mode: $range, amount_to: 500, amount_from: 200 }
                                        { mode: $percent, percent: 10 }
                                    ]
                                ]
                            }
                        ]
                    }
                ) {
                    id
                    name
                    fees {
                        currency_id
                        fee {
                            mode
                            amount_from
                            amount_to
                            fee
                            percent
                        }
                    }
                }
            }',
                'variables' => $data,
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            ['The amount_from and amount_to have an intersection range.'],
        ]);
    }

    public function testDeletePriceListFees(): void
    {
        $priceListFee = PriceListFee::orderBy('id', 'DESC')->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation ($id: ID!) {
                    deletePriceListFees(id: $id) {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $priceListFee[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $this->seeJson([
            'data' => [
                'deletePriceListFees' => [
                    'id' => (string) $priceListFee[0]->id,
                ],
            ],
        ]);
    }
}
