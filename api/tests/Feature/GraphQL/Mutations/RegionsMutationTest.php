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
