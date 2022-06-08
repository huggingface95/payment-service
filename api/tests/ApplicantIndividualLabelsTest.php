<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Accounts;

class ApplicantIndividualLabelsTest extends TestCase
{
    /**
     * ApplicantIndividualLabels Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateApplicantIndividual()
    {
        $this->login();
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
                $role_id: [ID]!
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
                    role_id: $role_id
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
            'phone' => '098'.str_pad(mt_rand(1,9),6,'0',STR_PAD_LEFT),
            'city' => 'New York',
            'address' => '1st Street',
            'birth_country_id' => 1,
            'birth_at' => '1986-01-02',
            'sex' => 'Male',
            'applicant_state_id' => 1,
            'account_manager_member_id' => 2,
            'role_id' => 1
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantIndividual' => [
                    'id' => $id['data']['createApplicantIndividual']['id']
                ],
            ],
        ]);
    }

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
            'hex_color_code' => '#'.mt_rand(100000, 999999)
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantIndividualLabel' => [
                    'id' => $id['data']['createApplicantIndividualLabel']['id']
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividualLabel()
    {
        $this->login();
        $label = \App\Models\ApplicantIndividualLabel::orderBy('id', 'DESC')->take(1)->get();
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
        $applicant = \App\Models\ApplicantIndividual::orderBy('id', 'DESC')->take(1)->get();
        $label = \App\Models\ApplicantIndividualLabel::orderBy('id', 'DESC')->take(1)->get();
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
                    'id' => $id['data']['attachApplicantIndividualLabel']['id']
                ],
            ],
        ]);
    }

    public function testDetachApplicantIndividualLabel()
    {
        $this->login();
        $applicant = \App\Models\ApplicantIndividual::orderBy('id', 'DESC')->take(1)->get();
        $label = \App\Models\ApplicantIndividualLabel::orderBy('id', 'DESC')->take(1)->get();
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
                    'id' => $id['data']['detachApplicantIndividualLabel']['id']
                ],
            ],
        ]);
    }

    public function testDeleteApplicantIndividualLabel()
    {
        $this->login();
        $label = \App\Models\ApplicantIndividualLabel::orderBy('id', 'DESC')->take(1)->get();
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
                    'id' => $id['data']['deleteApplicantIndividualLabel']['id']
                ],
            ],
        ]);
    }

    public function testDeleteApplicantIndividual()
    {
        $this->login();
        $applicant = \App\Models\ApplicantIndividual::orderBy('id', 'DESC')->take(1)->get();
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
                    'id' => $id['data']['deleteApplicantIndividual']['id']
                ],
            ],
        ]);
    }

}

