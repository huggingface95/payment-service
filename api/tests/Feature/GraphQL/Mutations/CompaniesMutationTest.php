<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

class CompaniesMutationTest extends TestCase
{
    /**
     * Company Mutation Testing
     *
     * @return void
     */
    public function testCreateCompanyNoAuth(): void
    {
        $seq = DB::table('companies')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE companies_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateCompany(
                $name: String!
                $email: EMAIL!
                $url: String!
                $country_id: ID!
                $phone: String
            ) {
            createCompany(
                name: $name
                email: $email
                url: $url
                country_id: $country_id
                phone: $phone
            ) {
                id
                name
                url
                email
                phone
                country {
                    id
                    name
                }
            }
        }
        ', [
            'name' =>  'Company_'.\Illuminate\Support\Str::random(5),
            'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
            'url' => 'company_'.\Illuminate\Support\Str::random(3).'.com',
            'phone' => '4916033222',
            'country_id' => 1,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateCompany(): void
    {
        $seq = DB::table('companies')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE companies_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateCompany(
                    $name: String!
                    $email: EMAIL!
                    $url: String!
                    $country_id: ID!
                    $phone: String
                ) {
                createCompany(
                    name: $name
                    email: $email
                    url: $url
                    country_id: $country_id
                    phone: $phone
                ) {
                    id
                    name
                    url
                    email
                    phone
                    country {
                        id
                        name
                    }
                }
            }',
                'variables' => [
                    'name' =>  'Company_'.\Illuminate\Support\Str::random(5),
                    'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'url' => 'company_'.\Illuminate\Support\Str::random(3).'.com',
                    'country_id' => 1,
                    'phone' => '4916033233665',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createCompany' => [
                    'id' => $id['data']['createCompany']['id'],
                    'name' => $id['data']['createCompany']['name'],
                    'url' => $id['data']['createCompany']['url'],
                    'email' => $id['data']['createCompany']['email'],
                    'country' => $id['data']['createCompany']['country'],
                    'phone' => $id['data']['createCompany']['phone'],
                ],
            ],
        ]);
    }

    public function testUpdateCompany(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateCompany(
                    $id: ID!
                    $name: String
                    $email: EMAIL
                    $url: String
                    $zip: String
                    $address: String
                    $city: String
                    $company_number: String
                    $country_id: ID
                    $contact_name: String
                    $phone: String
                    $reg_address: String
                    $tax_id: String
                    $incorporate_date: DateTimeUtc
                    $employees_id: ID
                    $type_of_industry_id: ID
                    $license_number: String
                    $exp_date: DateTimeUtc
                    $state_id: ID
                    $state_reason_id: ID
                    $reg_number: String
                    $entity_type: String
                    $logo_id: ID
                    $vv_token: String
                    $member_verify_url: String
                    $backoffice_login_url: String
                    $backoffice_forgot_password_url: String
                    $backoffice_support_url: String
                    $backoffice_support_email: EMAIL
                )
                {
                    updateCompany (
                        id: $id
                        name: $name
                        email: $email
                        url: $url
                        zip: $zip
                        address: $address
                        city: $city
                        company_number: $company_number
                        country_id: $country_id
                        contact_name: $contact_name
                        phone: $phone
                        reg_address: $reg_address
                        tax_id: $tax_id
                        incorporate_date: $incorporate_date
                        employees_id: $employees_id
                        type_of_industry_id: $type_of_industry_id
                        license_number: $license_number
                        exp_date: $exp_date
                        state_id: $state_id
                        state_reason_id: $state_reason_id
                        reg_number: $reg_number
                        entity_type: $entity_type
                        logo_id: $logo_id
                        vv_token: $vv_token
                        member_verify_url: $member_verify_url
                        backoffice_login_url: $backoffice_login_url
                        backoffice_forgot_password_url: $backoffice_forgot_password_url
                        backoffice_support_url: $backoffice_support_url
                        backoffice_support_email: $backoffice_support_email
                    )
                    {
                        id
                        name
                        url
                        email
                        zip
                        address
                        city
                        company_number
                        contact_name
                    }
                }',
                'variables' => [
                    'id' => (string) $company[0]->id,
                    'name' => 'name_'.\Illuminate\Support\Str::random(3),
                    'email' => 'company_'.\Illuminate\Support\Str::random(3).'@gmail.com',
                    'url' => 'url_'.\Illuminate\Support\Str::random(3),
                    'zip' => 'zip_'.\Illuminate\Support\Str::random(3),
                    'address' => 'address_'.\Illuminate\Support\Str::random(3),
                    'city' => 'city_'.\Illuminate\Support\Str::random(3),
                    'company_number' => 'company_number_'.\Illuminate\Support\Str::random(3),
                    'country_id' => 1,
                    'contact_name' => 'contact_name_'.\Illuminate\Support\Str::random(3),
                    'phone' => 'phone_'.\Illuminate\Support\Str::random(3),
                    'reg_address' => 'reg_address_'.\Illuminate\Support\Str::random(3),
                    'tax_id' => 'tax_id_'.\Illuminate\Support\Str::random(3),
                    'incorporate_date' => '2023-06-01T00:00:00.000Z',
                    'employees_id' => 1,
                    'type_of_industry_id' => 1,
                    'license_number' => 'license_number_'.\Illuminate\Support\Str::random(3),
                    'exp_date' => '2025-06-01T00:00:00.000Z',
                    'state_id' => 1,
                    'state_reason_id' => 1,
                    'reg_number' => 'reg_number_'.\Illuminate\Support\Str::random(3),
                    'entity_type' => 'entity_type_'.\Illuminate\Support\Str::random(3),
                    'logo_id' => 2,
                    'vv_token' => 'vv_token_'.\Illuminate\Support\Str::random(3),
                    'member_verify_url' => 'member_verify_url_'.\Illuminate\Support\Str::random(3),
                    'backoffice_login_url' => 'backoffice_login_url_'.\Illuminate\Support\Str::random(3),
                    'backoffice_forgot_password_url' => 'backoffice_forgot_password_url_'.\Illuminate\Support\Str::random(3),
                    'backoffice_support_url' => 'backoffice_support_url_'.\Illuminate\Support\Str::random(3),
                    'backoffice_support_email' => 'backoffice_support_email_'.\Illuminate\Support\Str::random(3).'@gmail.com',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateCompany' => [
                    'id' => $id['data']['updateCompany']['id'],
                    'name' => $id['data']['updateCompany']['name'],
                    'url' => $id['data']['updateCompany']['url'],
                    'email' => $id['data']['updateCompany']['email'],
                    'zip' => $id['data']['updateCompany']['zip'],
                    'address' => $id['data']['updateCompany']['address'],
                    'city' => $id['data']['updateCompany']['city'],
                    'company_number' => $id['data']['updateCompany']['company_number'],
                    'contact_name' => $id['data']['updateCompany']['contact_name'],
                ],
            ],
        ]);
    }

    public function testDeleteCompany(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'DESC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                mutation DeleteCompany(
                    $id: ID!
                )
                {
                    deleteCompany (
                        id: $id
                    )
                    {
                        id
                    }
                }',
                'variables' => [
                    'id' => (string) $company[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'deleteCompany' => [
                    'id' => $id['data']['deleteCompany']['id'],
                ],
            ],
        ]);
    }
}
