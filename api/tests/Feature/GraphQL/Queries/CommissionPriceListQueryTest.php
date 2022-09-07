<?php

namespace Tests;

use App\Models\CommissionPriceList;
use Illuminate\Support\Facades\DB;

class CommissionPriceListQueryTest extends TestCase
{

    /**
     * CommissionPriceList Query Testing
     *
     * @return void
     */

    public function testQueryCommissionPriceLists(): void
    {
        $this->login();

        $getRecord = DB::connection('pgsql_test')->table('commission_price_list')->orderBy('id', 'ASC')->get();

        $data =
            [
                'data' => [
                    'commissionPriceLists' => [
                        'data' => [[
                            'id' => strval($getRecord[0]->id),
                            'name' => $getRecord[0]->name,
                            'provider' => [
                                'id' => strval($getRecord[0]->provider_id),
                            ],
                            'payment_system' => [
                                'id' => strval($getRecord[0]->payment_system_id),
                            ],
                        ]],
                    ],
                ],
            ];
        $this->graphQL('
        {
            commissionPriceLists(first: 1) {
                data {
                    id
                    name
                    provider {
                      id
                    }
                    payment_system {
                      id
                    }
                }
            }
        }
        ')->seeJson($data);
    }

    public function testQueryCommissionPriceList(): void
    {
        $this->login();

        $getRecord = DB::connection('pgsql_test')->table('commission_price_list')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            query CommissionPriceList($id:ID!)
            {
                commissionPriceList(id: $id)
                {
                    id
                    name
                    provider {
                        id
                    }
                    payment_system {
                        id
                    }

                }
            }
        ', [
            'id' => strval($getRecord[0]->id),
        ])->seeJson([
            'data' => [
                'commissionPriceList' => [
                    'id' => strval($getRecord[0]->id),
                    'name' => $getRecord[0]->name,
                    'provider' => [
                        'id' => strval($getRecord[0]->provider_id),
                    ],
                    'payment_system' => [
                        'id' => strval($getRecord[0]->payment_system_id),
                    ],
                ],
            ],
        ]);
    }

    public function testQueryWithWhereCommissionPriceLists(): void
    {
        $this->login();

        $getRecord = DB::connection('pgsql_test')->table('commission_price_list')->orderBy('id', 'DESC')->get();

        $data =
            [
                [
                    'id' => strval($getRecord[0]->id),
                    'name' => $getRecord[0]->name,
                    'provider' => [
                        'id' => strval($getRecord[0]->provider_id),
                    ],
                    'payment_system' => [
                        'id' => strval($getRecord[0]->payment_system_id),
                    ],
                ],
            ];

        $this->graphQL('
        {
             commissionPriceLists(filter: { column: COMPANY_ID, value: 1 }) {
                data {
                    id
                    name
                    provider {
                        id
                    }
                    payment_system {
                        id
                    }
                }
             }
        }
        ')->seeJsonContains($data);
    }
}
