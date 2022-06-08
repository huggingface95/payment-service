<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Accounts;

class ApplicantCompanyModulesTest extends TestCase
{
    /**
     * ApplicantCompanyModules Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreateApplicantCompany()
    {
        $this->login();
        $this->graphQL('
            mutation CreateApplicantCompany(
                $name: String!
                $email: EMAIL!
                $url: String!
                $phone: String!
                $country_id: ID!
                $city: String!
                $address: String!
                $expires_at: Date!
                $applicant_state_id: ID!
                $account_manager_member_id: ID!
                $company_id: ID!
                $language_id: ID!
                $owner_relation_id: ID!
                $owner_position_id: ID!
            )
            {
                createApplicantCompany (
                    name: $name
                    email: $email
                    url: $url
                    phone: $phone
                    country_id: $country_id
                    city: $city
                    address: $address
                    expires_at: $expires_at
                    applicant_state_id: $applicant_state_id
                    account_manager_member_id: $account_manager_member_id
                    company_id: $company_id
                    language_id: $language_id
                    owner_relation_id: $owner_relation_id
                    owner_position_id: $owner_position_id
                )
                {
                    id
                }
            }
        ', [
            'name' =>  'AppCompany'.\Illuminate\Support\Str::random(3),
            'email' => 'applicant'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'url' => \Illuminate\Support\Str::random(6).'@com',
            'phone' => '098'.str_pad(mt_rand(1,9),6,'0',STR_PAD_LEFT),
            'country_id' => 1,
            'city' => 'New York',
            'address' => '1st Street',
            'expires_at' => '1986-01-02',
            'applicant_state_id' => 1,
            'account_manager_member_id' => 2,
            'company_id' => 1,
            'language_id' => 1,
            'owner_relation_id' => 1,
            'owner_position_id' => 1
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantCompany' => [
                    'id' => $id['data']['createApplicantCompany']['id']
                ],
            ],
        ]);
    }

    public function testCreateApplicantCompanyModule()
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
            'name' => 'Module_'.\Illuminate\Support\Str::random(7)
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantModule' => [
                    'id' => $id['data']['createApplicantModule']['id']
                ],
            ],
        ]);
    }

    public function testUpdateApplicantCompanyModule()
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

    public function testAttachApplicantCompanyModule()
    {
        $this->login();
        $applicant_company = \App\Models\ApplicantCompany::orderBy('id', 'DESC')->take(1)->get();
        $module = \App\Models\ApplicantModules::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation CreateApplicantCompanyModule(
                $applicant_company_id: ID!
                $applicant_module_id: [ID]
            )
            {
                createApplicantCompanyModule (
                    applicant_company_id: $applicant_company_id
                    applicant_module_id: $applicant_module_id
                    is_active: true
                )
                {
                    id
                }
            }
        ', [
            'applicant_company_id' => strval($applicant_company[0]->id),
            'applicant_module_id' => strval($module[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantCompanyModule' => [
                    'id' => $id['data']['createApplicantCompanyModule']['id']
                ],
            ],
        ]);
    }

    public function testDetachApplicantCompanyModule()
    {
        $this->login();
        $applicant_company = \App\Models\ApplicantCompany::orderBy('id', 'DESC')->take(1)->get();
        $module = \App\Models\ApplicantModules::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation DeleteApplicantCompanyModule(
                $applicant_company_id: ID!
                $applicant_module_id: [ID]
            )
            {
                deleteApplicantCompanyModule (
                    applicant_company_id: $applicant_company_id
                    applicant_module_id: $applicant_module_id
                )
                {
                    id
                }
            }
        ', [
            'applicant_company_id' => strval($applicant_company[0]->id),
            'applicant_module_id' => strval($module[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteApplicantCompanyModule' => [
                    'id' => $id['data']['deleteApplicantCompanyModule']['id']
                ],
            ],
        ]);
    }

    public function testDeleteApplicantCompanyModule()
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
                    'id' => $id['data']['deleteApplicantModule']['id']
                ],
            ],
        ]);
    }

    public function testDeleteApplicantCompany()
    {
        $this->login();
        $applicant = \App\Models\ApplicantCompany::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation DeleteApplicantCompany(
                $id: ID!
            )
            {
                deleteApplicantCompany (
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
                'deleteApplicantCompany' => [
                    'id' => $id['data']['deleteApplicantCompany']['id']
                ],
            ],
        ]);
    }

}

