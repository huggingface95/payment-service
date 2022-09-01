<?php

namespace Tests;

use App\Models\CommissionPriceList;
use Illuminate\Support\Facades\DB;

class CommissionPriceListTest extends TestCase
{

    /**
     * CommissionPriceList Testing
     *
     * @return void
     */
    public function testCreateCommissionPriceList()
    {
        $this->login();
        $this->graphQL('
            mutation (
                $name: String!
                $provider_id: ID!
                $payment_system_id: ID!
                $commission_template_id: ID!
                $company_id: ID!
                $region_id: ID!
            ) {
            createCommissionPriceList(
                name: $name
                provider_id: $provider_id
                payment_system_id: $payment_system_id
                commission_template_id: $commission_template_id
                company_id: $company_id
                region_id: $region_id
            ) {
                id
            }
            }
        ', [
            'name' => 'Test Commission Price List',
            'provider_id' => 1,
            'payment_system_id' => 1,
            'commission_template_id' => 1,
            'company_id' => 1,
            'region_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createCommissionPriceList' => [
                    'id' => $id['data']['createCommissionPriceList']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateCommissionPriceList()
    {
        $this->login();
        $getRecord = DB::connection('pgsql_test')->table('commission_price_list')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation (
                $id: ID!
                $name: String!
                $provider_id: ID!
                $payment_system_id: ID!
                $commission_template_id: ID!
            ) {
            updateCommissionPriceList(
                id: $id
                name: $name
                provider_id: $provider_id
                payment_system_id: $payment_system_id
                commission_template_id: $commission_template_id
            ) {
                id
                name
            }
            }
        ', [
            'id' => strval($getRecord[0]->id),
            'name' => 'Updated Commission Price List',
            'provider_id' => 1,
            'payment_system_id' => 1,
            'commission_template_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateCommissionPriceList' => [
                    'id' => $id['data']['updateCommissionPriceList']['id'],
                    'name' => $id['data']['updateCommissionPriceList']['name'],
                ],
            ],
        ]);
    }

    public function testQueryCommissionPriceLists()
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

    public function testQueryCommissionPriceList()
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

    public function testQueryWithWhereCommissionPriceLists()
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

    public function testDeleteCommissionPriceList()
    {
        $this->login();
        $getRecord = DB::connection('pgsql_test')->table('commission_price_list')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation (
                $id: ID!
            ) {
            deleteCommissionPriceList(
                id: $id
            ) {
                id
            }
            }
        ', [
            'id' => strval($getRecord[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteCommissionPriceList' => [
                    'id' => $id['data']['deleteCommissionPriceList']['id'],
                ],
            ],
        ]);
    }
}
