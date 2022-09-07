<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class RegionsTest extends TestCase
{
    /**
     * Regions Testing
     *
     * @return void
     */
    public function testCreateRegion(): void
    {
        $this->login();

        $seq = DB::table('regions')->max('id') + 1;
        DB::select('ALTER SEQUENCE regions_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateRegion($name: String!, $company_id: ID!) {
                createRegion(input: { name: $name, company_id: $company_id }) {
                    id
                }
            }
        ', [
            'name' => 'EU',
            'company_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJsonContains([
            [
                'id' => $id['data']['createRegion'][0]['id'],
            ],
        ]);
    }

    public function testUpdateRegion(): void
    {
        $this->login();

        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
            mutation UpdateRegion($id:ID!, $name: String!, $company_id: ID!) {
                updateRegion(id: $id, input: {name: $name, company_id: $company_id }) {
                    id
                }
            }
        ', [
            'id' => strval($region[0]->id),
            'name' =>  'US',
            'company_id' => 2,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateRegion' => [
                    'id' => $id['data']['updateRegion']['id'],
                ],
            ],
        ]);
    }

    public function testQueryRegionById(): void
    {
        $this->login();

        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        ($this->graphQL('
            query Region($id: ID!) {
                region(id: $id) {
                    id
                    name
                }
            }
        ', [
            'id' => strval($region[0]->id),
        ]))->seeJson([
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
            'name' => 'Afghanistan',
        ])->seeJsonContains([
            [
                'id' => strval($region[0]->id),
                'name' => strval($region[0]->name),
            ],
        ]);
    }

    public function testDeleteRegion(): void
    {
        $this->login();

        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'DESC')
            ->take(1)
            ->get();

        $this->graphQL('
            mutation DeleteRegion($id: ID!) {
                deleteRegion(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($region[0]->id),
        ]);

        $id = json_decode($this->response->getContent(), true);
        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteRegion']['id'],
            ],
        ]);
    }
}
