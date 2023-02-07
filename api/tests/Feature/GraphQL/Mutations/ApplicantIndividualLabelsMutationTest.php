<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualLabelsMutationTest extends TestCase
{
    /**
     * ApplicantIndividualLabels Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantIndividualLabelNoAuth(): void
    {
        $this->graphQL('
            mutation CreateApplicantIndividualLabel(
                $name: String!
                $hex_color_code: String!
            )
            {
                createApplicantIndividualLabel (
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

    public function testCreateApplicantIndividualLabel(): void
    {
        $this->postGraphQL([
            'query' => '
                mutation CreateApplicantIndividualLabel(
                    $name: String!
                    $hex_color_code: String!
                )
                {
                    createApplicantIndividualLabel (
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
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createApplicantIndividualLabel' => [
                    'id' => $id['data']['createApplicantIndividualLabel']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividualLabel(): void
    {
        $label = DB::connection('pgsql_test')
            ->table('applicant_individual_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation UpdateApplicantIndividualLabel(
                    $id: ID!
                    $name: String!
                )
                {
                    updateApplicantIndividualLabel (
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
            ]
    ],
    [
        "Authorization" => "Bearer " . $this->login()
    ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateApplicantIndividualLabel' => [
                    'id' => $id['data']['updateApplicantIndividualLabel']['id'],
                    'name' => $id['data']['updateApplicantIndividualLabel']['name'],
                ],
            ],
        ]);
    }

    public function testAttachApplicantIndividualLabel(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $label = DB::connection('pgsql_test')
            ->table('applicant_individual_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation AttachApplicantIndividualLabel(
                    $applicant_individual_id: ID!
                    $applicant_individual_label_id: [ID]
                )
                {
                    attachApplicantIndividualLabel (
                        applicant_individual_id: $applicant_individual_id
                        applicant_individual_label_id: $applicant_individual_label_id
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'applicant_individual_id' => (string) $applicant[0]->id,
                'applicant_individual_label_id' => (string) $label[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'attachApplicantIndividualLabel' => [
                    'id' => $id['data']['attachApplicantIndividualLabel']['id'],
                ],
            ],
        ]);
    }

    public function testDetachApplicantIndividualLabel(): void
    {
        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $label = DB::connection('pgsql_test')
            ->table('applicant_individual_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation DetachApplicantIndividualLabel(
                    $applicant_individual_id: ID!
                    $applicant_individual_label_id: [ID]
                )
                {
                    detachApplicantIndividualLabel (
                        applicant_individual_id: $applicant_individual_id
                        applicant_individual_label_id: $applicant_individual_label_id
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'applicant_individual_id' => (string) $applicant[0]->id,
                'applicant_individual_label_id' => (string) $label[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'detachApplicantIndividualLabel' => [
                    'id' => $id['data']['detachApplicantIndividualLabel']['id'],
                ],
            ],
        ]);
    }

    public function testDeleteApplicantIndividualLabel(): void
    {
        $label = DB::connection('pgsql_test')
            ->table('applicant_individual_labels')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL([
            'query' => '
                mutation DeleteApplicantIndividualLabel(
                    $id: ID!
                )
                {
                    deleteApplicantIndividualLabel (
                        id: $id
                    )
                    {
                        id
                    }
                }',
            'variables' => [
                'id' => (string) $label[0]->id,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteApplicantIndividualLabel' => [
                    'id' => $id['data']['deleteApplicantIndividualLabel']['id'],
                ],
            ],
        ]);
    }
}
