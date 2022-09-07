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

    public function testCreateApplicantCompanyLabel(): void
    {
        $this->login();

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
        ]);

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
        $this->login();

        $label = DB::connection('pgsql_test')->table('applicant_company_labels')->orderBy('id', 'DESC')->get();

        $this->graphQL('
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
            }
        ', [
            'id' => strval($label[0]->id),
            'name' => 'Label_'.\Illuminate\Support\Str::random(5),
        ]);
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
        $this->login();

        $applicant_company = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();
        $label = DB::connection('pgsql_test')->table('applicant_company_labels')->orderBy('id', 'DESC')->get();

        $this->graphQL('
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
            }
        ', [
            'applicant_company_id' => strval($applicant_company[0]->id),
            'applicant_company_label_id' => strval($label[0]->id),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'attachApplicantCompanyLabel' => [
                    'id' => $id['data']['attachApplicantCompanyLabel']['id'],
                ],
            ],
        ]);
    }

    public function testDetachApplicantCompanyLabel():void
    {
        $this->login();

        $applicant_company = DB::connection('pgsql_test')->table('applicant_companies')->orderBy('id', 'DESC')->get();
        $label = DB::connection('pgsql_test')->table('applicant_company_labels')->orderBy('id', 'DESC')->get();

        $this->graphQL('
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
            }
        ', [
            'applicant_company_id' => strval($applicant_company[0]->id),
            'applicant_company_label_id' => strval($label[0]->id),
        ]);

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
        $this->login();

        $label = DB::connection('pgsql_test')->table('applicant_company_labels')->orderBy('id', 'DESC')->get();

        $this->graphQL('
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
            }
        ', [
            'id' => strval($label[0]->id),
        ]);

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
