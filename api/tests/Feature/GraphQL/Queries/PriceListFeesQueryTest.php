<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\PriceListFee;
use App\Models\PriceListFeeCurrency;
use Tests\TestCase;

class PriceListFeesQueryTest extends TestCase
{
    public function testQueryPriceListFeesNoAuth(): void
    {
        $this->graphQL('
            {
                priceListFees {
                    data{
                        id
                        name
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPriceListFeesList(): void
    {
        $priceListFees = PriceListFee::orderBy('id', 'DESC')
            ->limit(10)
            ->get();

        $expect = [
            'data' => [
                'priceListFees' => [
                    'data' => [],
                ],
            ],
        ];

        foreach ($priceListFees as $priceListFee) {
            $expect['data']['priceListFees']['data'][] = [
                'id' => (string) $priceListFee['id'],
                'name' => (string) $priceListFee['name'],
                'fee_type' => [
                    'id' => (string) $priceListFee['type_id'],
                ],
                'operation_type' => [
                    'id' => (string) $priceListFee['operation_type_id'],
                ],
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    priceListFees {
                        data{
                            id
                            name
                            fee_type {
                                id
                            }
                            operation_type {
                                id
                            }
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson($expect);
    }

    /**
     * @dataProvider provide_testQueryPriceListFeesWithFilterByCondition
     */
    public function testQueryPriceListFeesWithFilterByCondition($cond, $value): void
    {
        $priceListFees = PriceListFee::where($cond, $value)
            ->orderBy('id', 'ASC')
            ->get();

        $expect = [
            'data' => [
                'priceListFees' => [
                    'data' => [],
                ],
            ],
        ];

        foreach ($priceListFees as $priceListFee) {
            $expect['data']['priceListFees']['data'][] = [
                'id' => (string) $priceListFee->id,
                'name' => (string) $priceListFee->name,
            ];
        }

        $this->postGraphQL(
            [
                'query' => 'query PriceListFees($id: Mixed) {
                    priceListFees (
                        filter: { column: '.strtoupper($cond).', operator: EQ, value: $id }
                    ) {
                        data{
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($expect);
    }

    public function testQueryPriceListFeesWithFilterByPaymentProviderId(): void
    {
        $priceListFees = PriceListFee::orderBy('id', 'DESC')->first();

        $paymentProvider = $priceListFees->paymentProvider()->first();

        $expect = [
            'id' => (string) $priceListFees->id,
            'name' => (string) $priceListFees->name,
        ];

        $this->postGraphQL(
            [
                'query' => 'query PriceListFees($id: Mixed) {
                    priceListFees (
                        filter: { column: HAS_PAYMENT_PROVIDER_FILTER_BY_ID, value: $id }
                    ) {
                        data{
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $paymentProvider->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($expect);
    }

    public function testQueryPriceListFeesWithFilterByPaymentSystemId(): void
    {
        $priceListFees = PriceListFee::orderBy('id', 'DESC')->first();

        $paymentSystem = $priceListFees->paymentSystem()->first();

        $expect = [
            'id' => (string) $priceListFees->id,
            'name' => (string) $priceListFees->name,
        ];

        $this->postGraphQL(
            [
                'query' => 'query PriceListFees($id: Mixed) {
                    priceListFees (
                        filter: { column: HAS_PAYMENT_SYSTEM_FILTER_BY_ID, value: $id }
                    ) {
                        data{
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $paymentSystem->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($expect);
    }

    public function testQueryPriceListFeesWithFilterByFeesId(): void
    {
        $priceListFees = PriceListFee::orderBy('id', 'ASC')->first();

        $fees = PriceListFeeCurrency::where('price_list_fee_id', $priceListFees->id)->first();

        $expect = [
            'id' => (string) $priceListFees->id,
            'name' => (string) $priceListFees->name,
        ];

        $this->postGraphQL(
            [
                'query' => 'query PriceListFees($id: Mixed) {
                    priceListFees (
                        filter: { column: HAS_FEES_FILTER_BY_ID, value: $id }
                    ) {
                        data{
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $fees->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($expect);
    }

    public function provide_testQueryPriceListFeesWithFilterByCondition()
    {
        return [
            ['id', '1'],
            ['price_list_id', '1'],
            ['type_id', '1'],
            ['operation_type_id', '1'],
            ['period_id', '1'],
            ['company_id', '1'],
        ];
    }
}
