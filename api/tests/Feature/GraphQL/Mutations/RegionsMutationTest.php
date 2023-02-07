<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

class RegionsMutationTest extends TestCase
{
    /**
     * Regions Mutation Testing
     *
     * @return void
     */

    public function testCreateRegionNoAuth(): void
    {
        $seq = DB::table('regions')
                ->max('id') + 1;

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
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateRegion(): void
    {
        $seq = DB::table('regions')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE regions_id_seq RESTART WITH '.$seq);

        $this->postGraphQL([
            'query' => '
                mutation CreateRegion($name: String!, $company_id: ID!) {
                    createRegion(input: { name: $name, company_id: $company_id }) {
                        id
                    }
                }',
            'variables' => [
                'name' => 'EU',
                'company_id' => 1,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createRegion']['id'],
            ],
        ]);
    }

    public function testUpdateRegion(): void
    {
        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation UpdateRegion($id:ID!, $name: String!, $company_id: ID!) {
                    updateRegion(id: $id, input: {name: $name, company_id: $company_id }) {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $region[0]->id,
                'name' =>  'US',
                'company_id' => 2,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
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

    public function testDeleteRegion(): void
    {
        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation DeleteRegion($id: ID!) {
                    deleteRegion(id: $id) {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $region[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteRegion']['id'],
            ],
        ]);
    }

}
