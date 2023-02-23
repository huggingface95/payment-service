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
    public function testCommissionTemplateNoAuth(): void
    {
        $this->graphQL('
             {
                commissionTemplates
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

    public function testQueryCommissionTemplatesFirst(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'ASC')
            ->first();

        $data =
            [
                'data' => [
                    'commissionTemplates' => [
                        'data' => [[
                            'id' => (string) $commissionTemplate->id,
                            'name' => $commissionTemplate->name,
                            'description' => $commissionTemplate->description,
                        ]],
                    ],
                ],
            ];

        $this->postGraphQL(
            [
                'query' => '
                {
                    commissionTemplates(first: 1) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson($data);
    }

    public function testQueryCommissionTemplate(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionTemplate($id:ID!)
                {
                    commissionTemplate(id: $id)
                    {
                        id
                        name
                        description
                    }
                }',
                'variables' => [
                    'id' => (string) $commissionTemplate[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'commissionTemplate' => [
                    'id' => (string) $commissionTemplate[0]->id,
                    'name' => $commissionTemplate[0]->name,
                    'description' => $commissionTemplate[0]->description,

                ],
            ],
        ]);
    }

    public function testQueryOrderByCommissionTemplate(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'DESC')
            ->get();

        $data =
            [
                [
                    'id' => (string) $commissionTemplate[0]->id,
                    'name' => $commissionTemplate[0]->name,
                    'description' => $commissionTemplate[0]->description,

                ],
            ];

        $this->postGraphQL(
            [
                'query' => '
                {
                     commissionTemplates (orderBy: {column:ID, order:DESC}) {
                        data {
                            id
                            name
                            description
                        }
                     }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function testQueryCommissionTemplateById(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionTemplate($id: Mixed) {
                    commissionTemplates(filter: { column: ID, value: $id }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'id' => $commissionTemplate->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionTemplate->id,
            'name' => (string) $commissionTemplate->name,
            'description' => (string) $commissionTemplate->description,
        ]);
    }

    public function testQueryCommissionTemplateByName(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query CommissionTemplate($name: Mixed) {
                    commissionTemplates(filter: { column: NAME, operator: LIKE, value: $name }) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
                'variables' => [
                    'name' => $commissionTemplate->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionTemplate->id,
            'name' => (string) $commissionTemplate->name,
            'description' => (string) $commissionTemplate->description,
        ]);
    }

    public function testQueryCommissionTemplateByPaymentProvider(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' => [
                    'id' => $commissionTemplate->payment_provider_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionTemplate->id,
            'name' => (string) $commissionTemplate->name,
            'description' => (string) $commissionTemplate->description,
        ]);
    }

    public function testQueryCommissionTemplateByBusinessActivity(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $activity = DB::connection('pgsql_test')
            ->table('commission_template_business_activity')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
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
                }',
                'variables' =>  [
                    'id' => $activity->commission_template_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionTemplate->id,
            'name' => (string) $commissionTemplate->name,
            'description' => (string) $commissionTemplate->description,
        ]);
    }

    public function testQueryCommissionTemplateByIsActive(): void
    {
        $commissionTemplate = DB::connection('pgsql_test')
            ->table('commission_template')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                {
                    commissionTemplates(
                        filter: { column: IS_ACTIVE, value: true }
                    ) {
                        data {
                            id
                            name
                            description
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $commissionTemplate->id,
            'name' => (string) $commissionTemplate->name,
            'description' => (string) $commissionTemplate->description,
        ]);
    }
}
