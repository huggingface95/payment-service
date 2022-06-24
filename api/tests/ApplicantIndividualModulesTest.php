<?php

class ApplicantIndividualModulesTest extends TestCase
{
    /**
     * ApplicantIndividualModules Testing
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
            'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
            'city' => 'New York',
            'address' => '1st Street',
            'birth_country_id' => 1,
            'birth_at' => '1986-01-02',
            'sex' => 'Male',
            'applicant_state_id' => 1,
            'account_manager_member_id' => 2,
            'role_id' => 1,
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

    public function testCreateApplicantIndividualModule()
    {
        $this->login();
        $this->graphQL('
            mutation CreateApplicantModule(
                $name: String!
            )
            {
                createApplicantModule (
                    name: $name
                )
                {
                    id
                }
            }
        ', [
            'name' => 'Module_'.\Illuminate\Support\Str::random(7),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantModule' => [
                    'id' => $id['data']['createApplicantModule']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateApplicantIndividualModule()
    {
        $this->login();
        $module = \App\Models\ApplicantModules::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation UpdateApplicantModule(
                $id: ID!
                $name: String!
            )
            {
                updateApplicantModule (
                    id: $id
                    name: $name
                )
                {
                    id
                    name
                }
            }
        ', [
            'id' => strval($module[0]->id),
            'name' => 'Module_updated_'.\Illuminate\Support\Str::random(5),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateApplicantModule' => [
                    'id' => $id['data']['updateApplicantModule']['id'],
                    'name' => $id['data']['updateApplicantModule']['name'],
                ],
            ],
        ]);
    }

    public function testAttachApplicantIndividualModule()
    {
        $this->login();
        $applicant = \App\Models\ApplicantIndividual::orderBy('id', 'DESC')->take(1)->get();
        $module = \App\Models\ApplicantModules::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation CreateApplicantIndividualModule(
                $applicant_individual_id: ID!
                $applicant_module_id: [ID]
            )
            {
                createApplicantIndividualModule (
                    applicant_individual_id: $applicant_individual_id
                    applicant_module_id: $applicant_module_id
                    is_active: true
                )
                {
                    id
                }
            }
        ', [
            'applicant_individual_id' => strval($applicant[0]->id),
            'applicant_module_id' => strval($module[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantIndividualModule' => [
                    'id' => $id['data']['createApplicantIndividualModule']['id'],
                ],
            ],
        ]);
    }

    public function testDetachApplicantIndividualModule()
    {
        $this->login();
        $applicant = \App\Models\ApplicantIndividual::orderBy('id', 'DESC')->take(1)->get();
        $module = \App\Models\ApplicantModules::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation DeleteApplicantIndividualModule(
                $applicant_individual_id: ID!
                $applicant_module_id: [ID]
            )
            {
                deleteApplicantIndividualModule (
                    applicant_individual_id: $applicant_individual_id
                    applicant_module_id: $applicant_module_id
                )
                {
                    id
                }
            }
        ', [
            'applicant_individual_id' => strval($applicant[0]->id),
            'applicant_module_id' => strval($module[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteApplicantIndividualModule' => [
                    'id' => $id['data']['deleteApplicantIndividualModule']['id'],
                ],
            ],
        ]);
    }

    public function testDeleteApplicantIndividualModule()
    {
        $this->login();
        $module = \App\Models\ApplicantModules::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation DeleteApplicantModule(
                $id: ID!
            )
            {
                deleteApplicantModule (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($module[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteApplicantModule' => [
                    'id' => $id['data']['deleteApplicantModule']['id'],
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
                    'id' => $id['data']['deleteApplicantIndividual']['id'],
                ],
            ],
        ]);
    }
}
