<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualQueryTest extends TestCase
{
    /**
     * ApplicantIndividual Query Testing
     *
     * @return void
     */

    public function testQueryApplicantIndividual(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            query ApplicantIndividual($id:ID!){
                applicantIndividual(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($applicant[0]->id),
        ])->seeJson([
            'data' => [
                'applicantIndividual' => [
                    'id' => strval($applicant[0]->id),
                ],
            ],
        ]);
    }

    public function testSetApplicantIndividualPassword(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
            mutation SetApplicantIndividualPassword(
                $id: ID!
                $password: String!
                $password_confirmation: String!
            )
            {
                setApplicantIndividualPassword (
                    id: $id
                    password: $password
                    password_confirmation: $password_confirmation
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($applicant[0]->id),
            'password' => '1234567Za',
            'password_confirmation' => '1234567Za',
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'setApplicantIndividualPassword' => [
                    'id' => $id['data']['setApplicantIndividualPassword']['id'],
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualOrderBy(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->orderBy('id', 'DESC')
            ->get();

        $this->graphQL('
        query {
            applicantIndividuals(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($applicant[0]->id),
            ],
        ]);
    }

    public function testQueryApplicantIndividualWhere(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->where('id', 1)
            ->first();

        $this->graphQL('
        query {
            applicantIndividuals(where: { column: ID, value: 1}) {
                data {
                    id
                }
            }
        }')->seeJsonContains([
            [
                'id' => strval($applicant->id),
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterById(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->first();

        $this->graphQL('
            query TestApplicantIndividualFilters($id: Mixed) {
                applicantIndividuals(filter: { column: ID, value: $id }) {
                    data {
                        id
                        first_name
                        email
                        url
                    }
                }
            }
        ', [
            'id' => strval($applicant->id),
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => [[
                        'id' => strval($applicant->id),
                        'first_name' => strval($applicant->first_name),
                        'email' => strval($applicant->email),
                        'url' => strval($applicant->url),
                    ]]
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByEmail(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->first();

        $this->graphQL('
            query TestApplicantIndividualFilters($email: Mixed) {
                applicantIndividuals(
                    filter: { column: EMAIL, operator: ILIKE, value: $email }
                ) {
                    data {
                        id
                        first_name
                        email
                        url
                    }
                }
            }
        ', [
            'email' => strval($applicant->email),
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => [[
                        'id' => strval($applicant->id),
                        'first_name' => strval($applicant->first_name),
                        'email' => strval($applicant->email),
                        'url' => strval($applicant->url),
                    ]]
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByCompanyId(): void
    {
        $this->login();

        $applicants = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->where('company_id', 1)
            ->get();

        foreach($applicants as $applicant) {
            $data[] = [
                'id' => strval($applicant->id),
                'first_name' => strval($applicant->first_name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ];
        }

        $this->graphQL('
            query TestApplicantIndividualFilters($company_id: Mixed) {
                applicantIndividuals(
                    filter: { column: COMPANY_ID, value: $company_id }
                ) {
                    data {
                        id
                        first_name
                        email
                        url
                    }
                }
            }
        ', [
            'company_id' => 1,
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByHasRiskLevel(): void
    {
        $this->login();

        $applicants = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->where('applicant_risk_level_id', 1)
            ->get();

        foreach ($applicants as $applicant) {
            $data[] = [
                'id' => strval($applicant->id),
                'first_name' => strval($applicant->first_name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ];
        }

        $this->graphQL('
            query TestApplicantIndividualFilters($id: Mixed) {
                applicantIndividuals(
                    filter: { column: HAS_RISK_LEVEL_FILTER_BY_ID, value: $id }
                ) {
                    data {
                        id
                        first_name
                        email
                        url
                    }
                }
            }
        ', [
            'id' => strval($applicant->applicant_risk_level_id),
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByHasStateReason(): void
    {
        $this->login();

        $applicants = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->where('applicant_state_reason_id', 1)
            ->get();

        foreach ($applicants as $applicant) {
            $data[] = [
                'id' => strval($applicant->id),
                'first_name' => strval($applicant->first_name),
                'email' => strval($applicant->email),
                'url' => strval($applicant->url),
            ];
        }

        $this->graphQL('
            query TestApplicantIndividualFilters($id: Mixed) {
                applicantIndividuals(
                    filter: { column: HAS_STATE_REASON_FILTER_BY_ID, value: $id }
                ) {
                    data {
                        id
                        first_name
                        email
                        url
                    }
                }
            }
        ', [
            'id' => 1,
        ])->seeJson([
            'data' => [
                'applicantIndividuals' => [
                    'data' => $data
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualFilterByOwners(): void
    {
        $this->login();

        $applicant = DB::connection('pgsql_test')
            ->table('applicant_individual')
            ->first();

        $this->graphQL('
            query {
                owners(orderBy: { column: ID, order: ASC }) {
                    id
                    first_name
                    email
                    url
                }
            }
        ')->seeJsonContains([
            'id' => strval($applicant->id),
            'first_name' => strval($applicant->first_name),
            'email' => strval($applicant->email),
            'url' => strval($applicant->url),
        ]);
    }
}
