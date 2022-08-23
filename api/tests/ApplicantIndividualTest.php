<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class ApplicantIndividualTest extends TestCase
{
    /**
     * ApplicantIndividual Testing
     *
     * @return void
     */

    public function testCreateApplicantIndividual()
    {
        $this->login();
        $seq = DB::table('applicant_individual')->max('id') + 1;
        DB::select('ALTER SEQUENCE applicant_individual_id_seq RESTART WITH '.$seq);
        $this->graphQL('
            mutation CreateApplicantIndividual(
                $first_name: String!
                $last_name: String!
                $email: EMAIL!
                $company_id: ID!
                $country_id: ID!
                $language_id: ID!
                $phone: String!
                $city: String!
                $address: String!
                $birth_country_id: ID!
                $birth_at: Date!
                $sex: Sex!
                $applicant_state_id: ID!
                $account_manager_member_id: ID!
            )
            {
                createApplicantIndividual (
                    first_name: $first_name
                    last_name: $last_name
                    email: $email
                    company_id: $company_id
                    country_id: $country_id
                    language_id: $language_id
                    phone: $phone
                    city: $city
                    address: $address
                    birth_country_id: $birth_country_id
                    birth_at: $birth_at
                    sex: $sex
                    applicant_state_id: $applicant_state_id
                    account_manager_member_id: $account_manager_member_id
                )
                {
                    id
                }
            }
        ', [
            'first_name' =>  'Applicant_'.\Illuminate\Support\Str::random(3),
            'last_name' => 'ApplicantLast_'.\Illuminate\Support\Str::random(3),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'company_id' => 1,
            'country_id' => 1,
            'language_id' => 1,
            'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
            'city' => 'New York',
            'address' => '1st Street',
            'birth_country_id' => 1,
            'birth_at' => '1986-01-02',
            'sex' => 'Male',
            'applicant_state_id' => 1,
            'account_manager_member_id' => 2,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantIndividual' => [
                    'id' => $id['data']['createApplicantIndividual']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividual()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();
        $this->graphQL('
            mutation UpdateApplicantIndividual(
                $id: ID!
                $email: EMAIL!
            )
            {
                updateApplicantIndividual (
                    id: $id
                    email: $email
                )
                {
                    id
                    email
                }
            }
        ', [
            'id' => strval($applicant[0]->id),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateApplicantIndividual' => [
                    'id' => $id['data']['updateApplicantIndividual']['id'],
                    'email' => $id['data']['updateApplicantIndividual']['email'],
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividual()
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

    public function testSetApplicantIndividualPassword()
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

    public function testQueryApplicantIndividualOrderBy()
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

    public function testQueryApplicantIndividualWhere()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            applicantIndividuals(where: { column: ID, value: 2}) {
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

    public function testDeleteApplicantIndividual()
    {
        $this->login();
        $applicant = DB::connection('pgsql_test')->table('applicant_individual')->orderByDesc('id')->get();
        $this->graphQL('
            mutation DeleteApplicantIndividual(
                $id: ID!
            )
            {
                deleteApplicantIndividual (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($applicant[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteApplicantIndividual' => [
                    'id' => $id['data']['deleteApplicantIndividual']['id'],
                ],
            ],
        ]);
    }
}
