<?php

namespace Tests;

use App\Models\Account;
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
        $company = Company::find(1);

        $this->postGraphQL(
            [
                'query' => 'query Company($id: ID!) {
                company(id: $id) {
                    id
                    name
                    url
                    email
                    company_number
                    company_modules {
                        id
                        is_active
                    }
                    contact_name
                    country {
                        id
                        name
                    }
                    zip
                    city
                    address
                    members {
                        id
                        fullname
                    }
                    members_count
                    projects_count
                    projects {
                        id
                        name
                    }
                    departments {
                        id
                        name
                    }
                    positions {
                        id
                        name
                    }
                    additional_fields_info
                    phone
                    reg_address
                    tax_id
                    incorporate_date
                    employees {
                        id
                        employees_number
                    }
                    type_of_industry {
                        id
                        name
                    }
                    license_number
                    exp_date
                    state {
                        id
                        name
                    }
                    revenues {
                        id
                        number
                    }
                    state_reason {
                        id
                        name
                    }
                    reg_number
                    entity_type
                    additional_fields_basic
                    additional_fields_settings
                    additional_fields_data
                    logo {
                        id
                        file_name
                    }
                    ledger_settings {
                        id
                    }
                    vv_token
                    member_verify_url
                    backoffice_login_url
                    backoffice_forgot_password_url
                    backoffice_support_url
                    backoffice_support_email
                    created_at
                    updated_at
                }
            }',
                'variables' => [
                    'id' => (string) $company->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'company' => [
                    'id' => $company->id !== null ? (string) $company->id : null,
                    'name' => $company->name !== null ? (string) $company->name : null,
                    'url' => $company->url !== null ? (string) $company->url : null,
                    'email' => $company->email !== null ? (string) $company->email : null,
                    'company_number' => $company->company_number !== null ? (string) $company->company_number : null,
                    'company_modules' => $company->modules ? $company->modules->map(function ($module) {
                        return [
                            'id' => $module->id !== null ? (string) $module->id : null,
                            'is_active' => $module->is_active,
                        ];
                    }) : [],
                    'contact_name' => $company->contact_name !== null ? (string) $company->contact_name : null,
                    'country' => $company->country ? [
                        'id' => $company->country->id !== null ? (string) $company->country->id : null,
                        'name' => $company->country->name !== null ? (string) $company->country->name : null,
                    ] : null,
                    'zip' => $company->zip !== null ? (string) $company->zip : null,
                    'city' => $company->city !== null ? (string) $company->city : null,
                    'address' => $company->address !== null ? (string) $company->address : null,
                    'members' => $company->members ? $company->members->map(function ($member) {
                        return [
                            'fullname' => $member->fullname !== null ? (string) $member->fullname : null,
                            'id' => $member->id !== null ? (string) $member->id : null,
                        ];
                    }) : [],
                    'members_count' => $company->members_count !== null ? (int) $company->members_count : null,
                    'projects_count' => $company->projects_count !== null ? (int) $company->projects_count : null,
                    'projects' => $company->projects ? $company->projects->map(function ($project) {
                        return [
                            'id' => $project->id !== null ? (string) $project->id : null,
                            'name' => $project->name !== null ? (string) $project->name : null,
                        ];
                    }) : [],
                    'departments' => $company->departments ? $company->departments->map(function ($department) {
                        return [
                            'id' => $department->id !== null ? (string) $department->id : null,
                            'name' => $department->name !== null ? (string) $department->name : null,
                        ];
                    }) : [],
                    'positions' => $company->positions ? $company->positions->map(function ($position) {
                        return [
                            'id' => $position->id !== null ? (string) $position->id : null,
                            'name' => $position->name !== null ? (string) $position->name : null,
                        ];
                    }) : [],
                    'additional_fields_info' => $company->additional_fields_info,
                    'phone' => $company->phone !== null ? (string) $company->phone : null,
                    'reg_address' => $company->reg_address !== null ? (string) $company->reg_address : null,
                    'tax_id' => $company->tax_id !== null ? (string) $company->tax_id : null,
                    'incorporate_date' => $company->incorporate_date !== null ? (string) $company->incorporate_date : null,
                    'employees' => $company->employees ? $company->employees->map(function ($employee) {
                        return [
                            'id' => $employee->id !== null ? (string) $employee->id : null,
                            'employees_number' => $employee->employees_number !== null ? (string) $employee->employees_number : null,
                        ];
                    }) : null,
                    'type_of_industry' => $company->type_of_industry ? [
                        'id' => $company->type_of_industry->id !== null ? (string) $company->type_of_industry->id : null,
                        'name' => $company->type_of_industry->name !== null ? (string) $company->type_of_industry->name : null,
                    ] : null,
                    'license_number' => $company->license_number !== null ? (string) $company->license_number : null,
                    'exp_date' => $company->exp_date,
                    'state' => $company->state ? [
                        'id' => $company->state->id !== null ? (string) $company->state->id : null,
                        'name' => $company->state->name !== null ? (string) $company->state->name : null,
                    ] : null,
                    'revenues' => $company->revenues ? $company->revenues->map(function ($revenue) {
                        return [
                            'id' => $revenue->id !== null ? (string) $revenue->id : null,
                            'number' => $revenue->number !== null ? (string) $revenue->number : null,
                        ];
                    }) : [],
                    'state_reason' => $company->state_reason ? [
                        'id' => $company->state_reason->id !== null ? (string) $company->state_reason->id : null,
                        'name' => $company->state_reason->name !== null ? (string) $company->state_reason->name : null,
                    ] : null,
                    'reg_number' => $company->reg_number !== null ? (string) $company->reg_number : null,
                    'entity_type' => $company->entity_type !== null ? (string) $company->entity_type : null,
                    'additional_fields_basic' => $company->additional_fields_basic,
                    'additional_fields_settings' => $company->additional_fields_settings,
                    'additional_fields_data' => $company->additional_fields_data,
                    'logo' => $company->logo ? [
                        'id' => $company->logo->id !== null ? (string) $company->logo->id : null,
                        'file_name' => $company->logo->file_name !== null ? (string) $company->logo->file_name : null,
                    ] : null,
                    'ledger_settings' => $company->ledger_settings ? [
                        'id' => $company->ledger_settings->id !== null ? (string) $company->ledger_settings->id : null,
                    ] : null,
                    'vv_token' => $company->vv_token,
                    'member_verify_url' => $company->member_verify_url,
                    'backoffice_login_url' => $company->backoffice_login_url,
                    'backoffice_forgot_password_url' => $company->backoffice_forgot_password_url,
                    'backoffice_support_url' => $company->backoffice_support_url,
                    'backoffice_support_email' => $company->backoffice_support_email,
                    'created_at' => $company->created_at !== null ? substr_replace($company->created_at, 'T', 10, 1) . '.000Z' : null,
                    'updated_at' => $company->updated_at !== null ? substr_replace($company->updated_at, 'T', 10, 1) . '.000Z' : null,
                ],
            ],
        ]);
    }

    public function testCompaniesOrderBy(): void
    {
        $company = Company::query()
            ->orderBy('id', 'ASC')
            ->first();

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
                            company_number
                            company_modules {
                                id
                                is_active
                            }
                            contact_name
                            country {
                                id
                                name
                            }
                            zip
                            city
                            address
                            members {
                                id
                                fullname
                            }
                            members_count
                            projects_count
                            projects {
                                id
                                name
                            }
                            departments {
                                id
                                name
                            }
                            positions {
                                id
                                name
                            }
                            additional_fields_info
                            phone
                            reg_address
                            tax_id
                            incorporate_date
                            employees {
                                id
                                employees_number
                            }
                            type_of_industry {
                                id
                                name
                            }
                            license_number
                            exp_date
                            state {
                                id
                                name
                            }
                            revenues {
                                id
                                number
                            }
                            state_reason {
                                id
                                name
                            }
                            reg_number
                            entity_type
                            additional_fields_basic
                            additional_fields_settings
                            additional_fields_data
                            logo {
                                id
                                file_name
                            }
                            ledger_settings {
                                id
                            }
                            vv_token
                            member_verify_url
                            backoffice_login_url
                            backoffice_forgot_password_url
                            backoffice_support_url
                            backoffice_support_email
                            created_at
                            updated_at
                        }
                        }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
                'id' => $company->id !== null ? (string) $company->id : null,
                'name' => $company->name !== null ? (string) $company->name : null,
                'url' => $company->url !== null ? (string) $company->url : null,
                'email' => $company->email !== null ? (string) $company->email : null,
                'company_number' => $company->company_number !== null ? (string) $company->company_number : null,
                'company_modules' => $company->modules ? $company->modules->map(function ($module) {
                    return [
                        'id' => $module->id !== null ? (string) $module->id : null,
                        'is_active' => $module->is_active,
                    ];
                }) : [],
                'contact_name' => $company->contact_name !== null ? (string) $company->contact_name : null,
                'country' => $company->country ? [
                    'id' => $company->country->id !== null ? (string) $company->country->id : null,
                    'name' => $company->country->name !== null ? (string) $company->country->name : null,
                ] : null,
                'zip' => $company->zip !== null ? (string) $company->zip : null,
                'city' => $company->city !== null ? (string) $company->city : null,
                'address' => $company->address !== null ? (string) $company->address : null,
                'members' => $company->members ? $company->members->map(function ($member) {
                    return [
                        'fullname' => $member->fullname !== null ? (string) $member->fullname : null,
                        'id' => $member->id !== null ? (string) $member->id : null,
                    ];
                }) : [],
                'members_count' => $company->members_count !== null ? (int) $company->members_count : null,
                'projects_count' => $company->projects_count !== null ? (int) $company->projects_count : null,
                'projects' => $company->projects ? $company->projects->map(function ($project) {
                    return [
                        'id' => $project->id !== null ? (string) $project->id : null,
                        'name' => $project->name !== null ? (string) $project->name : null,
                    ];
                }) : [],
                'departments' => $company->departments ? $company->departments->map(function ($department) {
                    return [
                        'id' => $department->id !== null ? (string) $department->id : null,
                        'name' => $department->name !== null ? (string) $department->name : null,
                    ];
                }) : [],
                'positions' => $company->positions ? $company->positions->map(function ($position) {
                    return [
                        'id' => $position->id !== null ? (string) $position->id : null,
                        'name' => $position->name !== null ? (string) $position->name : null,
                    ];
                }) : [],
                'additional_fields_info' => $company->additional_fields_info,
                'phone' => $company->phone !== null ? (string) $company->phone : null,
                'reg_address' => $company->reg_address !== null ? (string) $company->reg_address : null,
                'tax_id' => $company->tax_id !== null ? (string) $company->tax_id : null,
                'incorporate_date' => $company->incorporate_date !== null ? (string) $company->incorporate_date : null,
                'employees' => $company->employees ? $company->employees->map(function ($employee) {
                    return [
                        'id' => $employee->id !== null ? (string) $employee->id : null,
                        'employees_number' => $employee->employees_number !== null ? (string) $employee->employees_number : null,
                    ];
                }) : null,
                'type_of_industry' => $company->type_of_industry ? [
                    'id' => $company->type_of_industry->id !== null ? (string) $company->type_of_industry->id : null,
                    'name' => $company->type_of_industry->name !== null ? (string) $company->type_of_industry->name : null,
                ] : null,
                'license_number' => $company->license_number !== null ? (string) $company->license_number : null,
                'exp_date' => $company->exp_date,
                'state' => $company->state ? [
                    'id' => $company->state->id !== null ? (string) $company->state->id : null,
                    'name' => $company->state->name !== null ? (string) $company->state->name : null,
                ] : null,
                'revenues' => $company->revenues ? $company->revenues->map(function ($revenue) {
                    return [
                        'id' => $revenue->id !== null ? (string) $revenue->id : null,
                        'number' => $revenue->number !== null ? (string) $revenue->number : null,
                    ];
                }) : [],
                'state_reason' => $company->state_reason ? [
                    'id' => $company->state_reason->id !== null ? (string) $company->state_reason->id : null,
                    'name' => $company->state_reason->name !== null ? (string) $company->state_reason->name : null,
                ] : null,
                'reg_number' => $company->reg_number !== null ? (string) $company->reg_number : null,
                'entity_type' => $company->entity_type !== null ? (string) $company->entity_type : null,
                'additional_fields_basic' => $company->additional_fields_basic,
                'additional_fields_settings' => $company->additional_fields_settings,
                'additional_fields_data' => $company->additional_fields_data,
                'logo' => $company->logo ? [
                    'id' => $company->logo->id !== null ? (string) $company->logo->id : null,
                    'file_name' => $company->logo->file_name !== null ? (string) $company->logo->file_name : null,
                ] : null,
                'ledger_settings' => $company->ledger_settings ? [
                    'id' => $company->ledger_settings->id !== null ? (string) $company->ledger_settings->id : null,
                ] : null,
                'vv_token' => $company->vv_token,
                'member_verify_url' => $company->member_verify_url,
                'backoffice_login_url' => $company->backoffice_login_url,
                'backoffice_forgot_password_url' => $company->backoffice_forgot_password_url,
                'backoffice_support_url' => $company->backoffice_support_url,
                'backoffice_support_email' => $company->backoffice_support_email,
                'created_at' => $company->created_at !== null ? substr_replace($company->created_at, 'T', 10, 1) . '.000Z' : null,
                'updated_at' => $company->updated_at !== null ? substr_replace($company->updated_at, 'T', 10, 1) . '.000Z' : null,
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

    public function testCompaniesFilterByAccountId(): void
    {
        $account = Account::find(1);
        $company = $account->company()->first();

        $this->postGraphQL(
            [
                'query' => 'query Company($id: Mixed) {
                    companies(filter: { column: HAS_ACCOUNTS_FILTER_BY_ID, value: $id }) {
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
                    'id' => (string) $account->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains([
            [
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
        ]);
    }
}
