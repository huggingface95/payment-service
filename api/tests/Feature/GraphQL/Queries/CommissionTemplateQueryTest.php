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

        $getRecord = DB::connection('pgsql_test')->table('commission_template')->orderBy('id', 'ASC')->get();

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

        $getRecord = DB::connection('pgsql_test')->table('commission_template')->orderBy('id', 'DESC')->get();

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

        $getRecord = DB::connection('pgsql_test')->table('commission_template')->orderBy('id', 'DESC')->get();

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
}
