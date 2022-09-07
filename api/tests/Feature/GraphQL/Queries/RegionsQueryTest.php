<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

class RegionsQueryTest extends TestCase
{
    /**
     * Regions Query Testing
     *
     * @return void
     */

    public function testQueryRegionById(): void
    {
        $this->login();

        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
            query Region($id: ID!) {
                region(id: $id) {
                    id
                    name
                }
            }
        ', [
            'id' => strval($region[0]->id),
        ])->seeJson([
            'data' => [
                'region' => [
                    'id' => strval($region[0]->id),
                    'name' => strval($region[0]->name),
                ],
            ],
        ]);
    }

    public function testQueryRegionsByCompanyId(): void
    {
        $this->login();

        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'ASC')
            ->take(1)
            ->get();

        $this->graphQL('
            query {
                regions(filter: { column: COMPANY_ID, value: 1 }) {
                    data {
                        id
                        name
                    }
                }
            }
            ')->seeJsonContains([
            [
                'id' => strval($region[0]->id),
                'name' => strval($region[0]->name),
            ],
        ]);
    }

    public function testQueryRegionsByCountryId(): void
    {
        $this->login();

        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'ASC')
            ->take(1)
            ->get();

        $this->graphQL('
            query {
                regions(filter: { column: , HAS_COUNTRIES_FILTER_BY_ID, value: 1 }) {
                    data {
                        id
                        name
                    }
                }
            }
            ')->seeJsonContains([
            [
                'id' => strval($region[0]->id),
                'name' => strval($region[0]->name),
            ],
        ]);
    }

    public function testQueryRegionsByCountryName(): void
    {
        $this->login();

        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'ASC')
            ->take(1)
            ->get();

        $this->graphQL('
            query Regions ($name: Mixed) {
                regions(
                    filter: { column: HAS_COUNTRIES_FILTER_BY_NAME, operator: LIKE, value: $name }
                ) {
                    data {
                        id
                        name
                    }
                }
            }
            ', [
               'name' => "Afghanistan",
        ])->seeJsonContains([
            [
                'id' => strval($region[0]->id),
                'name' => strval($region[0]->name),
            ],
        ]);
    }
}
