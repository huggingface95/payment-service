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
    public function testCreateCommissionTemplateNoAuth(): void
    {
        $seq = DB::table('commission_template')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE commission_template_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateCommissionTemplate(
                $name: String!
                $description: String
                $payment_provider_id: ID!
                $company_id: ID!
            ) {
            createCommissionTemplate(
                input: {
                    name: $name
                    description: $description
                    payment_provider_id: $payment_provider_id
                    company_id: $company_id
                    payment_system_id: [1]
                    business_activity: [1]
                }
            ) {
                id
            }
            }
        ', [
            'name' => 'TestCommissionTemplate_'.\Illuminate\Support\Str::random(5),
            'description' => 'TemplateDecs_'.\Illuminate\Support\Str::random(5),
            'payment_provider_id' => 1,
            'company_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateCommissionTemplate(): void
    {
        $seq = DB::table('commission_template')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE commission_template_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateCommissionTemplate(
                    $name: String!
                    $description: String
                    $payment_provider_id: ID!
                    $company_id: ID!
                ) {
                createCommissionTemplate(
                    input: {
                        name: $name
                        description: $description
                        payment_provider_id: $payment_provider_id
                        company_id: $company_id
                        payment_system_id: [1]
                        business_activity: [1]
                    }
                ) {
                    id
                }
                }',
                'variables' => [
                    'name' => 'TestCommissionTemplate_'.\Illuminate\Support\Str::random(5),
                    'description' => 'TemplateDecs_'.\Illuminate\Support\Str::random(5),
                    'payment_provider_id' => 1,
                    'company_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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
        $template = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateCommissionTemplate(
                    $id: ID!
                    $name: String!
                    $description: String!
                ) {
                updateCommissionTemplate(
                    id: $id
                    input: {
                        name: $name
                        description: $description
                        payment_system_id: [1]
                        business_activity: [1]
                    }
                ) {
                    id
                    name
                }
                }',
                'variables' => [
                    'id' => (string) $template[0]->id,
                    'name' => 'Updated Commission Template',
                    'description' => 'Updated Description',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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
        $template = DB::connection('pgsql_test')
            ->table('commission_template')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteCommissionTemplate(
                    $id: ID!
                ) {
                deleteCommissionTemplate(
                    id: $id
                ) {
                    id
                }
                }',
                'variables' => [
                    'id' => (string) $template[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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
