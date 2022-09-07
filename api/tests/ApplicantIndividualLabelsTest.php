<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualLabelsTest extends TestCase
{
    /**
     * ApplicantIndividualLabels Testing
     *
     * @return void
     */
    public function testCreateApplicantIndividualLabel()
    {
        $this->login();
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

    public function testUpdateApplicantIndividualLabel()
    {
        $this->login();
        $label = DB::connection('pgsql_test')->table('applicant_individual_labels')->orderBy('id', 'DESC')->get();
        $this->graphQL('
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
            }
        ', [
            'id' => strval($label[0]->id),
            'name' => 'Label_'.\Illuminate\Support\Str::random(5),
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

    public function testAttachApplicantIndividualLabel()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();
        $label = DB::connection('pgsql_test')->table('applicant_individual_labels')->orderBy('id', 'DESC')->get();
        $this->graphQL('
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
            }
        ', [
            'applicant_individual_id' => strval($applicant[0]->id),
            'applicant_individual_label_id' => strval($label[0]->id),
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

    public function testDetachApplicantIndividualLabel()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();
        $label = DB::connection('pgsql_test')->table('applicant_individual_labels')->orderBy('id', 'DESC')->get();
        $this->graphQL('
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
            }
        ', [
            'applicant_individual_id' => strval($applicant[0]->id),
            'applicant_individual_label_id' => strval($label[0]->id),
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

    public function testDeleteApplicantIndividualLabel()
    {
        $this->login();
        $label = DB::connection('pgsql_test')->table('applicant_individual_labels')->orderBy('id', 'DESC')->get();
        $this->graphQL('
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
            }
        ', [
            'id' => strval($label[0]->id),
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
