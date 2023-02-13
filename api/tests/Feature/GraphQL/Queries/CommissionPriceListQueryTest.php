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
    public function testCommissionPriceListNoAuth(): void
    {
        $this->graphQL('
             {
                commissionPriceLists
                 {
                    data {
                        id
                        name
                    }
                }
             }')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryCommissionPriceLists(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->orderBy('id', 'ASC')
            ->get();

        $data =
            [
                'data' => [
                    'commissionPriceLists' => [
                        'data' => [[
                            'id' => (string) $commissionPriceList[0]->id,
                            'name' => $commissionPriceList[0]->name,
                            'provider' => [
                                'id' => (string) $commissionPriceList[0]->provider_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $commissionPriceList[0]->payment_system_id,
                            ],
                        ]],
                    ],
                ],
            ];

        $this->postGraphQL(
            [
                'query' => '
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
        ', ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson($data);
    }

    public function testQueryCommissionPriceListsOrderBy(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->orderBy('id', 'ASC')
            ->first();

        $data =
            [
                'data' => [
                    'commissionPriceLists' => [
                        'data' => [[
                            'id' => (string) $commissionPriceList->id,
                            'name' => $commissionPriceList->name,
                            'provider' => [
                                'id' => (string) $commissionPriceList->provider_id,
                            ],
                            'payment_system' => [
                                'id' => (string) $commissionPriceList->payment_system_id,
                            ],
                        ]],
                    ],
                ],
            ];

        $this->postGraphQL(
            [
                'query' => '
                {
                    commissionPriceLists(first:1, orderBy:{column: ID, order:ASC}) {
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
        ', ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson($data);
    }

    public function testQueryCommissionPriceList(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => (string) $commissionPriceList[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'commissionPriceList' => [
                    'id' => (string) $commissionPriceList[0]->id,
                    'name' => $commissionPriceList[0]->name,
                    'provider' => [
                        'id' => (string) $commissionPriceList[0]->provider_id,
                    ],
                    'payment_system' => [
                        'id' => (string) $commissionPriceList[0]->payment_system_id,
                    ],
                ],
            ],
        ]);
    }

    public function testQueryCommissionPriceListByCompanyID(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->orderBy('id', 'ASC')
            ->get();

        $data =
            [
                [
                    'id' => (string) $commissionPriceList[0]->id,
                    'name' => $commissionPriceList[0]->name,
                    'provider' => [
                        'id' => (string) $commissionPriceList[0]->provider_id,
                    ],
                    'payment_system' => [
                        'id' => (string) $commissionPriceList[0]->payment_system_id,
                    ],
                ],
            ];

        $this->postGraphQL(
            [
                'query' => '
                query CommissionPriceLists ($company_id: Mixed) {
                     commissionPriceLists(filter: { column: COMPANY_ID, value: $company_id }) {
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
                }',
                'variables' => [
                    'company_id' => $commissionPriceList[0]->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryCommissionPriceListByPaymentProvider(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionPriceList($id: Mixed) {
                    commissionPriceLists(
                        filter: { column: HAS_PAYMENT_PROVIDER_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $commissionPriceList->provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionPriceList->id,
            'name' => (string) $commissionPriceList->name,
        ]);
    }

    public function testQueryCommissionPriceListByCommissionTemplate(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionPriceList($id: Mixed) {
                    commissionPriceLists(
                        filter: { column: HAS_COMMISSION_TEMPLATE_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $commissionPriceList->commission_template_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionPriceList->id,
            'name' => (string) $commissionPriceList->name,
        ]);
    }

    public function testQueryCommissionPriceListByPaymentSystem(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionPriceList($id: Mixed) {
                    commissionPriceLists(
                        filter: { column: HAS_PAYMENT_SYSTEM_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $commissionPriceList->payment_system_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionPriceList->id,
            'name' => (string) $commissionPriceList->name,
        ]);
    }

    public function testQueryCommissionPriceListById(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionPriceList($id: Mixed) {
                    commissionPriceLists(
                        filter: { column: ID, value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $commissionPriceList->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionPriceList->id,
            'name' => (string) $commissionPriceList->name,
        ]);
    }

    public function testQueryCommissionPriceListByRegionId(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionPriceList($id: Mixed) {
                    commissionPriceLists(
                        filter: { column: REGION_ID, value: $id }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $commissionPriceList->region_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionPriceList->id,
            'name' => (string) $commissionPriceList->name,
        ]);
    }
}
