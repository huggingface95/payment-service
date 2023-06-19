<?php

namespace Tests;

use App\Models\CommissionPriceList;
use Illuminate\Support\Facades\DB;

class CommissionPriceListMutationTest extends TestCase
{
    /**
     * CommissionPriceList Mutation Testing
     *
     * @return void
     */
    public function testCreateCommissionPriceListNoAuth(): void
    {
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
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateCommissionPriceList(): void
    {
        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'name' => 'Test Commission Price List',
                    'provider_id' => 1,
                    'payment_system_id' => 1,
                    'commission_template_id' => 1,
                    'company_id' => 1,
                    'region_id' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createCommissionPriceList' => [
                    'id' => $id['data']['createCommissionPriceList']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateCommissionPriceList(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => (string) $commissionPriceList[0]->id,
                    'name' => 'Updated Commission Price List',
                    'provider_id' => 1,
                    'payment_system_id' => 1,
                    'commission_template_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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

    public function testDeleteCommissionPriceList(): void
    {
        $commissionPriceList = DB::connection('pgsql_test')
            ->table('commission_price_list')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation (
                    $id: ID!
                ) {
                deleteCommissionPriceList(
                    id: $id
                ) {
                    id
                }
                }',
                'variables' => [
                    'id' => (string) $commissionPriceList[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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
