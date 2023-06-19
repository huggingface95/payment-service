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
    public function testRegionsNoAuth(): void
    {
        $this->graphQL('
            {
                regions {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryRegionById(): void
    {
        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query Region($id: ID!) {
                    region(id: $id) {
                        id
                        name
                    }
                }',
                'variables' => [
                    'id' => (string) $region->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'region' => [
                    'id' => (string) $region->id,
                    'name' => (string) $region->name,
                ],
            ],
        ]);
    }

    public function testQueryRegionsByCompanyId(): void
    {
        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query Regions ($id: Mixed){
                    regions(filter: { column: COMPANY_ID, value: $id }) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $region->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $region->id,
                'name' => (string) $region->name,
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

        $country = DB::connection('pgsql_test')
            ->table('region_countries')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query Region ($id: Mixed) {
                    regions(filter: { column: , HAS_COUNTRIES_FILTER_BY_ID, value: $id }) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'id' => $country->country_id,
                ], ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
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

        $this->postGraphQL(
            [
                'query' => '
                query Regions ($name: Mixed) {
                    regions(
                        filter: { column: HAS_COUNTRIES_FILTER_BY_NAME, operator: LIKE, value: $name }
                    ) {
                        data {
                            id
                            name
                        }
                    }
                }',
                'variables' => [
                    'name' => 'Cuba',
                ], ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => strval($region[0]->id),
                'name' => strval($region[0]->name),
            ],
        ]);
    }
}
