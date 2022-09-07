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

        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();

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

        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();

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

        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();

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

        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();

        $this->graphQL('
        query {
            applicantIndividuals(where: { column: ID, value: 1}) {
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
}
