<?php

class ApplicantCompanyLabelsTest extends TestCase
{
    /**
     * ApplicantCompanyLabels Testing
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
            'phone' => '098'.str_pad(mt_rand(1, 9), 6, '0', STR_PAD_LEFT),
            'country_id' => 1,
            'city' => 'New York',
            'address' => '1st Street',
            'expires_at' => '1986-01-02',
            'applicant_state_id' => 1,
            'account_manager_member_id' => 2,
            'company_id' => 1,
            'language_id' => 1,
            'owner_relation_id' => 1,
            'owner_position_id' => 1,
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantCompany' => [
                    'id' => $id['data']['createApplicantCompany']['id'],
                ],
            ],
        ]);
    }

    public function testCreateApplicantCompanyLabel()
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

    public function testUpdateApplicantCompanyLabel()
    {
        $this->login();
        $label = \App\Models\ApplicantCompanyLabel::orderBy('id', 'DESC')->take(1)->get();
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

    public function testAttachApplicantCompanyLabel()
    {
        $this->login();
        $applicant_company = \App\Models\ApplicantCompany::orderBy('id', 'DESC')->take(1)->get();
        $label = \App\Models\ApplicantCompanyLabel::orderBy('id', 'DESC')->take(1)->get();
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

    public function testDetachApplicantCompanyLabel()
    {
        $this->login();
        $applicant_company = \App\Models\ApplicantCompany::orderBy('id', 'DESC')->take(1)->get();
        $label = \App\Models\ApplicantCompanyLabel::orderBy('id', 'DESC')->take(1)->get();
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

    public function testDeleteApplicantCompanyLabel()
    {
        $this->login();
        $label = \App\Models\ApplicantCompanyLabel::orderBy('id', 'DESC')->take(1)->get();
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
                    'id' => $id['data']['deleteApplicantCompany']['id'],
                ],
            ],
        ]);
    }
}
