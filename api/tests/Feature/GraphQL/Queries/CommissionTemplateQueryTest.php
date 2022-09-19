<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class CommissionTemplateQueryTest extends TestCase
{

    /**
     * CommissionTemplate Query Testing
     *
     * @return void
     */

    public function testQueryCommissionTemplatesFirst(): void
    {
        $this->login();

        $getRecord = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'ASC')
            ->get();

        $data =
            [
                'data' => [
                    'commissionTemplates' => [
                        'data' => [[
                            'id' => strval($getRecord[0]->id),
                            'name' => $getRecord[0]->name,
                            'description' => $getRecord[0]->description,
                        ]],
                    ],
                ],
            ];

        $this->graphQL('
        {
            commissionTemplates(first: 1) {
                data {
                    id
                    name
                    description
                }
            }
        }
        ')->seeJson($data);
    }

    public function testQueryCommissionTemplate(): void
    {
        $this->login();

        $getRecord = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            query CommissionTemplate($id:ID!)
            {
                commissionTemplate(id: $id)
                {
                    id
                    name
                    description
                }
            }
        ', [
            'id' => strval($getRecord[0]->id),
        ])->seeJson([
            'data' => [
                'commissionTemplate' => [
                    'id' => strval($getRecord[0]->id),
                    'name' => $getRecord[0]->name,
                    'description' => $getRecord[0]->description,

                ],
            ],
        ]);
    }

    public function testQueryOrderByCommissionTemplate(): void
    {
        $this->login();

        $getRecord = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'DESC')
            ->get();

        $data =
            [
                [
                    'id' => strval($getRecord[0]->id),
                    'name' => $getRecord[0]->name,
                    'description' => $getRecord[0]->description,

                ],
            ];

        $this->graphQL('
        {
             commissionTemplates (orderBy: {column:ID, order:DESC}) {
                data {
                    id
                    name
                    description
                }
             }
        }
        ')->seeJsonContains($data);
    }

    public function testQueryCommissionTemplateById(): void
    {
        $this->login();

        $list = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $this->graphQL('
            query CommissionTemplate($id: Mixed) {
                commissionTemplates(filter: { column: ID, value: $id }) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'id' => $list->id
        ])->seeJsonContains([
            'id' => strval($list->id),
            'name' => strval($list->name),
            'description' => strval($list->description),
        ]);
    }

    public function testQueryCommissionTemplateByName(): void
    {
        $this->login();

        $list = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $this->graphQL('
            query CommissionTemplate($name: Mixed) {
                commissionTemplates(filter: { column: NAME, operator: LIKE, value: $name }) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'name' => $list->name
        ])->seeJsonContains([
            'id' => strval($list->id),
            'name' => strval($list->name),
            'description' => strval($list->description),
        ]);
    }

    public function testQueryCommissionTemplateByPaymentProvider(): void
    {
        $this->login();

        $list = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $this->graphQL('
            query CommissionTemplate($id: Mixed) {
                commissionTemplates(
                    filter: { column: HAS_PAYMENT_PROVIDER_FILTER_BY_ID, value: $id }
                ) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'id' => $list->payment_provider_id
        ])->seeJsonContains([
            'id' => strval($list->id),
            'name' => strval($list->name),
            'description' => strval($list->description),
        ]);
    }

    public function testQueryCommissionTemplateByBusinessActivity(): void
    {
        $this->login();

        $list = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $activity = DB::connection('pgsql_test')
            ->table('commission_template_business_activity')
            ->first();

        $this->graphQL('
            query CommissionTemplate($id: Mixed) {
                commissionTemplates(
                    filter: { column: HAS_BUSINESS_ACTIVITY_FILTER_BY_ID, value: $id }
                ) {
                    data {
                        id
                        name
                        description
                    }
                }
            }
        ', [
            'id' => $activity->commission_template_id
        ])->seeJsonContains([
            'id' => strval($list->id),
            'name' => strval($list->name),
            'description' => strval($list->description),
        ]);
    }
}
