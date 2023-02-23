<?php

namespace Tests;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompaniesQueryTest extends TestCase
{
    /**
     * Company Query Testing
     *
     * @return void
     */
    public function testCompaniesNoAuth(): void
    {
        $this->graphQL('
            {
                companies {
                    data {
                        id
                        name
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryCompany(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->first();

        $this->postGraphQL(
            [
                'query' => 'query Company($id:ID!){
                    company(id: $id) {
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
                    'id' => (string) $company->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'company' => [
                    'id' => (string) $company->id,
                    'name' => (string) $company->name,
                    'url' => (string) $company->url,
                    'email' => (string) $company->email,
                    'zip' => (string) $company->zip,
                    'address' => (string) $company->address,
                    'city' => (string) $company->city,
                    'company_number' => (string) $company->company_number,
                    'contact_name' => (string) $company->contact_name,
                ],
            ],
        ]);
    }

    public function testCompaniesOrderBy(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => '
                query {
                    companies(orderBy: { column: ID, order: ASC }) {
                        data {
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
                        }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByName(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($name: Mixed) {
                    companies(filter: { column: NAME, operator: ILIKE, value: $name }) {
                        data {
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
                    }
                }
                ',
                'variables' => [
                    'name' => (string) $company[0]->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByEmail(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($email: Mixed) {
                    companies(filter: { column: EMAIL, operator: ILIKE, value: $email }) {
                        data {
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
                }
            }',
                'variables' => [
                    'email' => (string) $company[0]->email,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByUrl(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($url: Mixed) {
                    companies(filter: { column: URL, operator: ILIKE, value: $url }) {
                        data {
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
                }
            }',
                'variables' => [
                    'url' => (string) $company[0]->url,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByRegNumber(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($reg_number: Mixed) {
                    companies(filter: { column: REG_NUMBER, operator: ILIKE, value: $reg_number }) {
                        data {
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
                }
            }',
                'variables' => [
                    'reg_number' => (string) $company[0]->reg_number,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByEntityType(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($entity_type: Mixed) {
                    companies(filter: { column: ENTITY_TYPE, operator: ILIKE, value: $entity_type }) {
                        data {
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
                }
            }',
                'variables' => [
                    'entity_type' => (string) $company[0]->entity_type,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByCountryId(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($country_id: Mixed) {
                    companies(filter: { column: COUNTRY_ID, value: $country_id }) {
                        data {
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
                }
            }',
                'variables' => [
                    'country_id' => (string) $company[0]->country_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByPaymentProviders(): void
    {
        $paymentProvider = DB::connection('pgsql_test')
            ->table('payment_provider')
            ->orderBy('id', 'ASC')
            ->first();

        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->where('id', $paymentProvider->company_id)
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($payment_provider: Mixed) {
                    companies(filter: { column: HAS_PAYMENT_PROVIDERS_FILTER_BY_ID, value: $payment_provider }) {
                        data {
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
                }
            }',
                'variables' => [
                    'payment_provider' => (string) $paymentProvider->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByPaymentSystems(): void
    {
        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->orderBy('id', 'ASC')
            ->get();

        $paymentSystem = Company::with(['paymentSystem'])->first();

        $this->postGraphQL(
            [
                'query' => 'query Company($payment_system: Mixed) {
                    companies(filter: { column: HAS_PAYMENT_SYSTEM_FILTER_BY_ID, value: $payment_system }) {
                        data {
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
                }
            }',
                'variables' => [
                    'payment_system' => (string) $paymentSystem->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }

    public function testCompaniesFilterByRegions(): void
    {
        $region = DB::connection('pgsql_test')
            ->table('regions')
            ->orderBy('id', 'ASC')
            ->first();

        $company = DB::connection('pgsql_test')
            ->table('companies')
            ->where('id', $region->company_id)
            ->orderBy('id', 'ASC')
            ->get();

        $this->postGraphQL(
            [
                'query' => 'query Company($region: Mixed) {
                    companies(filter: { column: HAS_REGIONS_FILTER_BY_ID, value: $region }) {
                        data {
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
                }
            }',
                'variables' => [
                    'region' => (string) $region->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => (string) $company[0]->id,
                'name' => (string) $company[0]->name,
                'url' => (string) $company[0]->url,
                'email' => (string) $company[0]->email,
                'zip' => (string) $company[0]->zip,
                'address' => (string) $company[0]->address,
                'city' => (string) $company[0]->city,
                'company_number' => (string) $company[0]->company_number,
                'contact_name' => (string) $company[0]->contact_name,
            ],
        ]);
    }
}
