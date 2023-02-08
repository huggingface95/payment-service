<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantCompanyLabelsMutationTest extends TestCase
{
    /**
     * ApplicantCompanyLabels Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantCompanyLabelNoAuth(): void
    {
        $this->graphQL('
            mutation CreateApplicantCompanyLabel(
                $name: String!
                $hex_color_code: String!
            )
            {
                createApplicantCompanyLabel (
                    name: $name
                    hex_color_code: $hex_color_code
                )
                {
                    id
                }
            }
        ', [
            'name' => 'Label_'.\Illuminate\Support\Str::random(5),
            'hex_color_code' => '#'.mt_rand(100000, 999999),
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateApplicantCompanyLabel(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                mutation CreateApplicantCompanyLabel(
                    $name: String!
                    $hex_color_code: String!
                )
                {
                    createApplicantCompanyLabel (
                        name: $name
                        hex_color_code: $hex_color_code
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'name' => 'Label_'.\Illuminate\Support\Str::random(5),
                    'hex_color_code' => '#'.mt_rand(100000, 999999),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantCompanyLabel' => [
                    'id' => $id['data']['createApplicantCompanyLabel']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantCompanyLabel(): void
    {
        $label = DB::connection('pgsql_test')
            ->table('applicant_company_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateApplicantCompanyLabel(
                    $id: ID!
                    $name: String!
                )
                {
                    updateApplicantCompanyLabel (
                        id: $id
                        name: $name
                    )
                    {
                        id
                        name
                    }
                }',
                'variables' => [
                    'id' => (string) $label[0]->id,
                    'name' => 'Label_'.\Illuminate\Support\Str::random(5),
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantCompanyLabel' => [
                    'id' => $id['data']['updateApplicantCompanyLabel']['id'],
                    'name' => $id['data']['updateApplicantCompanyLabel']['name'],
                ],
            ],
        ]);
    }

    public function testAttachApplicantCompanyLabel(): void
    {
        $applicant_company = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $label = DB::connection('pgsql_test')
            ->table('applicant_company_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation AttachApplicantCompanyLabel(
                    $applicant_company_id: ID!
                    $applicant_company_label_id: [ID]
                )
                {
                    attachApplicantCompanyLabel (
                        applicant_company_id: $applicant_company_id
                        applicant_company_label_id: $applicant_company_label_id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $applicant_company[0]->id,
                    'applicant_company_label_id' => (string) $label[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'attachApplicantCompanyLabel' => [
                    'id' => $id['data']['attachApplicantCompanyLabel']['id'],
                ],
            ],
        ]);
    }

    public function testDetachApplicantCompanyLabel(): void
    {
        $applicant_company = DB::connection('pgsql_test')
            ->table('applicant_companies')
            ->orderBy('id', 'DESC')
            ->get();

        $label = DB::connection('pgsql_test')
            ->table('applicant_company_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DetachApplicantCompanyLabel(
                    $applicant_company_id: ID!
                    $applicant_company_label_id: [ID]
                )
                {
                    detachApplicantCompanyLabel (
                        applicant_company_id: $applicant_company_id
                        applicant_company_label_id: $applicant_company_label_id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'applicant_company_id' => (string) $applicant_company[0]->id,
                    'applicant_company_label_id' => (string) $label[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'detachApplicantCompanyLabel' => [
                    'id' => $id['data']['detachApplicantCompanyLabel']['id'],
                ],
            ],
        ]);
    }

    public function testDeleteApplicantCompanyLabel(): void
    {
        $label = DB::connection('pgsql_test')
            ->table('applicant_company_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteApplicantCompanyLabel(
                    $id: ID!
                )
                {
                    deleteApplicantCompanyLabel (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $label[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantCompanyLabel' => [
                    'id' => $id['data']['deleteApplicantCompanyLabel']['id'],
                ],
            ],
        ]);
    }
}
