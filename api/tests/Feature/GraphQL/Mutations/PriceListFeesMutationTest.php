<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\PriceListFee;
use Tests\TestCase;

class PriceListFeesMutationTest extends TestCase
{

    public function testCreatePriceListFeesNoAuth(): void
    {
        $data = [
            'name' => 'Test 1-' . time(),
            'type_id' => 1,
            'period_id' => 1,
            'operation_type_id' => 1,
            'price_list_id' => 1,
        ];

        $this->graphQL('
        mutation (
            $name: String!
            $type_id: ID
            $period_id: ID
            $operation_type_id: ID
            $price_list_id: ID!
        ) {
            createPriceListFees(
                name: $name
                type_id: $type_id
                period_id: $period_id
                operation_type_id: $operation_type_id
                price_list_id: $price_list_id
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
        $this->login();

        $data = [
            'name' => 'Test 1-' . time(),
            'type_id' => 1,
            'period_id' => 1,
            'operation_type_id' => 1,
            'price_list_id' => 1,
        ];

        $fees = [
            [
                'currency_id' => '1',
                'fee_mode_id' => '1', 'fee' => 10, 'fee_from' => 100, 'fee_to' => 300,
            ],
            [
                'currency_id' => '1',
                'fee_mode_id' => '2', 'fee' => 15, 'fee_from' => null, 'fee_to' => null,
            ],
            [
                'currency_id' => '2',
                'fee_mode_id' => '1', 'fee' => 20, 'fee_from' => 120, 'fee_to' => 220,
            ],
            [
                'currency_id' => '2',
                'fee_mode_id' => '3', 'fee' => 12, 'fee_from' => null, 'fee_to' => null,
            ],
            [
                'currency_id' => '3',
                'fee_mode_id' => '3', 'fee' => 13, 'fee_from' => null, 'fee_to' => null,
            ],
        ];

        $this->graphQL('
        mutation (
            $name: String!
            $type_id: ID
            $period_id: ID
            $operation_type_id: ID
            $price_list_id: ID!
        ) {
            createPriceListFees(
                name: $name
                type_id: $type_id
                period_id: $period_id
                operation_type_id: $operation_type_id
                price_list_id: $price_list_id
                fees: [
                    {
                        currency_id: 1
                        fee_modes: [
                            { fee_mode_id: 1, fee: 10, fee_from: 100, fee_to: 300 }
                            { fee_mode_id: 2, fee: 15 }
                        ]
                    },
                    {
                        currency_id: 2
                        fee_modes: [
                            { fee_mode_id: 1, fee: 20, fee_from: 120, fee_to: 220 }
                            { fee_mode_id: 3, fee: 12 }
                        ]
                    },
                    {
                        currency_id: 3
                        fee_modes: [
                            { fee_mode_id: 3, fee: 13 }
                        ]
                    }
                ]
            ) {
                id
                name
                fees {
                    currency_id
                    fee
                    fee_from
                    fee_mode_id
                    fee_to
                }
            }
        }
        ', $data);

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
        $this->login();

        $priceListFee = PriceListFee::first();

        $data = [
            'id' => $priceListFee->id,
            'name' => 'Test 2-' . time(),
            'type_id' => 2,
            'period_id' => 2,
            'operation_type_id' => 2,
            'price_list_id' => 1,
        ];

        $fees = [
            [
                'currency_id' => '1',
                'fee_mode_id' => '1', 'fee' => 15, 'fee_from' => 120, 'fee_to' => 320,
            ],
            [
                'currency_id' => '1',
                'fee_mode_id' => '2', 'fee' => 25, 'fee_from' => null, 'fee_to' => null,
            ],
            [
                'currency_id' => '3',
                'fee_mode_id' => '3', 'fee' => 30, 'fee_from' => null, 'fee_to' => null,
            ],
        ];

        $this->graphQL('
        mutation (
            $id: ID!
            $name: String!
            $type_id: ID
            $period_id: ID
            $operation_type_id: ID
            $price_list_id: ID!
        ) {
            updatePriceListFees(
                id: $id
                name: $name
                type_id: $type_id
                period_id: $period_id
                operation_type_id: $operation_type_id
                price_list_id: $price_list_id
                fees: [
                    {
                        currency_id: 1
                        fee_modes: [
                            { fee_mode_id: 1, fee: 15, fee_from: 120, fee_to: 320 }
                            { fee_mode_id: 2, fee: 25 }
                        ]
                    },
                    {
                        currency_id: 3
                        fee_modes: [
                            { fee_mode_id: 3, fee: 30 }
                        ]
                    }
                ]
            ) {
                id
                name
                fees {
                    currency_id
                    fee
                    fee_from
                    fee_mode_id
                    fee_to
                }
            }
        }
        ', $data);

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
        $this->login();

        $data = [
            'name' => 'Test 1-' . time(),
            'type_id' => 1,
            'period_id' => 1,
            'operation_type_id' => 1,
            'price_list_id' => 1,
        ];

        $this->graphQL('
        mutation (
            $name: String!
            $type_id: ID
            $period_id: ID
            $operation_type_id: ID
            $price_list_id: ID!
        ) {
            createPriceListFees(
                name: $name
                type_id: $type_id
                period_id: $period_id
                operation_type_id: $operation_type_id
                price_list_id: $price_list_id
                fees: [
                    {
                        currency_id: 1
                        fee_modes: [
                            { fee_mode_id: 1, fee: 10, fee_from: 100, fee_to: 300 }
                            { fee_mode_id: 1, fee: 10, fee_from: 150, fee_to: 350 }
                        ]
                    }
                ]
            ) {
                id
                name
                fees {
                    currency_id
                    fee
                    fee_from
                    fee_mode_id
                    fee_to
                }
            }
        }
        ', $data)->seeJson([
            'fees' => ['The fee_from and fee_to have an intersection range.'],
        ]);
    }

    public function testDeletePriceListFees(): void
    {
        $this->login();

        $priceListFee = PriceListFee::first();

        $this->graphQL('
        mutation ($id: ID!) {
            deletePriceListFees(id: $id) {
                id
            }
        }
        ', [
            'id' => (string) $priceListFee->id,
        ]);

        $this->seeJson([
            'data' => [
                'deletePriceListFees' => [
                    'id' => (string) $priceListFee->id,
                ],
            ],
        ]);
    }

}
