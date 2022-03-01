<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Accounts;

class AccountsTest extends TestCase
{
    /**
     * Accounts Testing
     *
     * @return void
     */
    public function testCreateAccount()
    {
        $this->graphQL('
            mutation CreateAccount(
              $currency_id: ID!
              $client_id: ID!
              $owner_id: ID!
              $account_number: String!
              $account_type: String!
              $payment_provider_id: ID!
              $commission_template_id: ID!
              $account_state: String!
              $account_name: String!
              $is_primary: Boolean!
            ) {
              createAccount (
                currency_id: $currency_id
                client_id: $client_id
                owner_id: $owner_id
                account_number: $account_number
                account_type: $account_type
                payment_provider_id: $payment_provider_id
                commission_template_id: $commission_template_id
                account_state: $account_state
                account_name: $account_name
                is_primary: $is_primary
              )
              {
                id
              }
            }
        ');
        $data = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantBankingAccess' => [
                    'id' => $data['data']['createApplicantBankingAccess']['id'],
                    'applicant_individual' => [
                        'id' => $data['data']['createApplicantBankingAccess']['applicant_individual']['id']
                    ],
                    'applicant_company' => [
                        'id' => $data['data']['createApplicantBankingAccess']['applicant_company']['id']
                    ],
                    'member' => [
                        'id' => $data['data']['createApplicantBankingAccess']['member']['id']
                    ],
                    'can_sign_payment' => $data['data']['createApplicantBankingAccess']['can_sign_payment'],
                    'can_create_payment' => $data['data']['createApplicantBankingAccess']['can_create_payment'],
                    'contact_administrator' => $data['data']['createApplicantBankingAccess']['contact_administrator'],
                    'daily_limit' => $data['data']['createApplicantBankingAccess']['daily_limit'],
                    'monthly_limit' => $data['data']['createApplicantBankingAccess']['monthly_limit'],
                    'operation_limit' => $data['data']['createApplicantBankingAccess']['operation_limit']
                ],
            ],
        ]);
    }
    /*
    public function testQueryApplicantBankingAccess()
    {
        $applicantBankingAccess = ApplicantBankingAccess::factory()->create();
        $getRecord = ApplicantBankingAccess::orderBy('id')->take(1)->get();
        $data =
            [
                'data' => [
                    'applicantBankingAccess' => [
                        'data' => [[
                            'id' => strval($getRecord[0]->id),
                            'applicant_individual' => [
                                'id' => strval($getRecord[0]->applicant_individual_id)
                            ],
                            'applicant_company' => [
                                'id' => strval($getRecord[0]->applicant_company_id)
                            ],
                            'member' => [
                                'id' => strval($getRecord[0]->member_id)
                            ],
                            'can_create_payment' => $getRecord[0]->can_create_payment,
                            'can_sign_payment' => $getRecord[0]->can_sign_payment,
                            'contact_administrator' => $getRecord[0]->contact_administrator,
                            'daily_limit' => intval($getRecord[0]->daily_limit),
                            'monthly_limit' => intval($getRecord[0]->monthly_limit),
                            'operation_limit' => intval($getRecord[0]->operation_limit)
                        ]],
                    ],
                ],
            ];

        $this->graphQL('
        {
            applicantBankingAccess(first: 1) {
                data {
                    id
                    applicant_individual {
                        id
                    }
                    applicant_company {
                        id
                    }
                    member {
                        id
                    }
                    can_create_payment
                    can_sign_payment
                    contact_administrator
                    daily_limit
                    monthly_limit
                    operation_limit
                }
            }    
        }
        ')->seeJson($data);
    }

    public function testQueryWhereApplicantBankingAccess()
    {
        $applicantBankingAccess = ApplicantBankingAccess::factory()->create();
        $data =
            [
                [
                'id' => strval($applicantBankingAccess->id),
                'applicant_individual' => [
                    'id' => strval($applicantBankingAccess->applicant_individual_id)
                ],
                'applicant_company' => [
                    'id' => strval($applicantBankingAccess->applicant_company_id)
                ],
                'member' => [
                    'id' => strval($applicantBankingAccess->member_id)
                ],
                'can_create_payment' => $applicantBankingAccess->can_create_payment,
                'can_sign_payment' => $applicantBankingAccess->can_sign_payment,
                'contact_administrator' => $applicantBankingAccess->contact_administrator,
                'daily_limit' => intval($applicantBankingAccess->daily_limit),
                'monthly_limit' => intval($applicantBankingAccess->monthly_limit),
                'operation_limit' => intval($applicantBankingAccess->operation_limit)
                ]
            ];

        $this->graphQL('
            query ApplicantBankingAccess($applicant_individual_id:Mixed)
            {
                 applicantBankingAccess(
                    where: { column: APPLICANT_INDIVIDUAL_ID, operator: EQ, value: $applicant_individual_id }
                    orderBy: { column: ID, order: DESC }
                    ) 
                    {
                        data {
                            id
                            applicant_individual {
                              id
                            }
                            applicant_company {
                              id
                            }
                            member {
                              id
                            }
                            can_create_payment
                            can_sign_payment
                            contact_administrator
                            daily_limit
                            monthly_limit
                            operation_limit
                        }
                    }
            }
        ',[
            'applicant_individual_id' => strval($applicantBankingAccess->applicant_individual_id)
        ])->seeJsonContains($data);
    }

    public function testCreateApplicantBankingAccess()
    {
        $this->graphQL('
            mutation {
              createApplicantBankingAccess(
                applicant_individual_id: 1
                applicant_company_id: 1
                member_id: 2
                can_create_payment: true
                can_sign_payment: true
                contact_administrator: true
                daily_limit: 20000
                monthly_limit: 150000
                operation_limit: 1000
              ) {
                id
                applicant_individual {
                  id
                }
                applicant_company {
                  id
                }
                member {
                  id
                }
                can_create_payment
                can_sign_payment
                contact_administrator
                daily_limit
                monthly_limit
                operation_limit
              }
            }
        ');
        $data = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createApplicantBankingAccess' => [
                    'id' => $data['data']['createApplicantBankingAccess']['id'],
                    'applicant_individual' => [
                        'id' => $data['data']['createApplicantBankingAccess']['applicant_individual']['id']
                    ],
                    'applicant_company' => [
                        'id' => $data['data']['createApplicantBankingAccess']['applicant_company']['id']
                    ],
                    'member' => [
                        'id' => $data['data']['createApplicantBankingAccess']['member']['id']
                    ],
                    'can_sign_payment' => $data['data']['createApplicantBankingAccess']['can_sign_payment'],
                    'can_create_payment' => $data['data']['createApplicantBankingAccess']['can_create_payment'],
                    'contact_administrator' => $data['data']['createApplicantBankingAccess']['contact_administrator'],
                    'daily_limit' => $data['data']['createApplicantBankingAccess']['daily_limit'],
                    'monthly_limit' => $data['data']['createApplicantBankingAccess']['monthly_limit'],
                    'operation_limit' => $data['data']['createApplicantBankingAccess']['operation_limit']
                ],
            ],
        ]);
    }

    public function testUpdateApplicantBankingAccess()
    {
        $applicantBankingAccess = ApplicantBankingAccess::factory()->create();

        $this->graphQL('
            mutation UpdateApplicantBankingAccess(
              $id: ID!
              $applicant_individual_id: ID!
              $applicant_company_id: ID!
              $member_id: ID!
            ) {
              updateApplicantBankingAccess(
                id: $id
                applicant_individual_id: $applicant_individual_id
                applicant_company_id: $applicant_company_id
                member_id: $member_id
                can_create_payment: true
                can_sign_payment: false
                contact_administrator: false
                daily_limit: 50000
                monthly_limit: 500000
                operation_limit: 2000
              ) {
                id
                applicant_individual {
                  id
                }
                applicant_company {
                  id
                }
                member {
                  id
                }
                can_create_payment
                can_sign_payment
                contact_administrator
                daily_limit
                monthly_limit
                operation_limit
              }
            }
        ', [
            'id' => strval($applicantBankingAccess->id),
            'applicant_individual_id' =>  1,
            'applicant_company_id' =>  1,
            'member_id' =>  2
        ]);
        $data = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateApplicantBankingAccess' => [
                    'id' => $data['data']['updateApplicantBankingAccess']['id'],
                    'applicant_individual' => [
                        'id' => $data['data']['updateApplicantBankingAccess']['applicant_individual']['id']
                    ],
                    'applicant_company' => [
                        'id' => $data['data']['updateApplicantBankingAccess']['applicant_company']['id']
                    ],
                    'member' => [
                        'id' => $data['data']['updateApplicantBankingAccess']['member']['id']
                    ],
                    'can_sign_payment' => $data['data']['updateApplicantBankingAccess']['can_sign_payment'],
                    'can_create_payment' => $data['data']['updateApplicantBankingAccess']['can_create_payment'],
                    'contact_administrator' => $data['data']['updateApplicantBankingAccess']['contact_administrator'],
                    'daily_limit' => $data['data']['updateApplicantBankingAccess']['daily_limit'],
                    'monthly_limit' => $data['data']['updateApplicantBankingAccess']['monthly_limit'],
                    'operation_limit' => $data['data']['updateApplicantBankingAccess']['operation_limit']
                ],
            ],
        ]);
    }

    public function testDeleteApplicantBankingAccess()
    {
        $applicantBankingAccess = ApplicantBankingAccess::factory()->create();

        $this->graphQL('
            mutation (
                $id: ID!
            ) {
            deleteApplicantBankingAccess(
                id: $id
            ) {
                id
            }
            }
        ', [
            'id' => strval($applicantBankingAccess->id)
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteApplicantBankingAccess' => [
                    'id' => $id['data']['deleteApplicantBankingAccess']['id'],
                ],
            ],
        ]);
    }
    */

}

