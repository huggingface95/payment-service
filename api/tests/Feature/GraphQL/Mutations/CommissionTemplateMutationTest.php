<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class CommissionTemplateMutationTest extends TestCase
{

    /**
     * CommissionTemplate Mutation Testing
     *
     * @return void
     */
    public function testCreateCommissionTemplate(): void
    {
        $this->login();

        $seq = DB::table('commission_template')->max('id') + 1;
        DB::select('ALTER SEQUENCE commission_template_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateCommissionTemplate(
                $name: String!
                $description: String
                $payment_provider_id: ID!

            ) {
            createCommissionTemplate(
                name: $name
                description: $description
                payment_provider_id: $payment_provider_id
            ) {
                id
            }
            }
        ', [
            'name' => 'TestCommissionTemplate_'.\Illuminate\Support\Str::random(5),
            'description' => 'TemplateDecs_'.\Illuminate\Support\Str::random(5),
            'payment_provider_id' => 1,
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createCommissionTemplate' => [
                    'id' => $id['data']['createCommissionTemplate']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateCommissionTemplate(): void
    {
        $this->login();

        $template = DB::connection('pgsql_test')->table('commission_template')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation UpdateCommissionTemplate(
                $id: ID!
                $name: String!
                $description: String!
            ) {
            updateCommissionTemplate(
                id: $id
                name: $name
                description: $description
            ) {
                id
                name
            }
            }
        ', [
            'id' => strval($template[0]->id),
            'name' => 'Updated Commission Template',
            'description' => 'Updated Description',
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateCommissionTemplate' => [
                    'id' => $id['data']['updateCommissionTemplate']['id'],
                    'name' => $id['data']['updateCommissionTemplate']['name'],
                ],
            ],
        ]);
    }

    public function testDeleteCommissionTemplate(): void
    {
        $this->login();

        $getRecord = DB::connection('pgsql_test')->table('commission_template')->orderBy('id', 'DESC')->get();

        $this->graphQL('
            mutation DeleteCommissionTemplate(
                $id: ID!
            ) {
            deleteCommissionTemplate(
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
                'deleteCommissionTemplate' => [
                    'id' => $id['data']['deleteCommissionTemplate']['id'],
                ],
            ],
        ]);
    }
}
