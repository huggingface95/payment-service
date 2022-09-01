<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\PriceListFee;
use Tests\TestCase;

class PriceListFeesQueryTest extends TestCase
{

    public function testQueryPriceListFeesNoAuth(): void
    {
        $this->graphQL('
            {
                priceListFees {
                    id
                    name
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryPriceListFeesList(): void
    {
        $this->login();

        $priceListFees = PriceListFee::orderBy('id', 'DESC')->limit(10)->get();

        $expect = [
            'data' => [
                'priceListFees' => [],
            ],
        ];

        foreach ($priceListFees as $priceListFee) {
            $expect['data']['priceListFees'][] = [
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

        $this->graphQL('
        {
            priceListFees {
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
        ')->seeJson($expect);
    }

}
