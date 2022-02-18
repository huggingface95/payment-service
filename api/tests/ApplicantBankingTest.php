<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\ApplicantBankingAccess;

class ApplicantBankingTest extends TestCase
{
    /**
     * CommissionPriceList Testing
     *
     * @return void
     */
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
                    'applicant_individual_id' => $data['data']['createApplicantBankingAccess']['applicant_individual_id'],
                    'applicant_company_id' => $data['data']['createApplicantBankingAccess']['applicant_company_id'],
                    'member_id' => $data['data']['createApplicantBankingAccess']['member_id'],
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
    public function testQueryWithWhereCommissionPriceLists()
    {
        $getRecord = CommissionPriceList::where(['payment_system_id' => 1])->get();

        $data =
            [
                [
                    'id' => strval($getRecord[0]->id),
                    'name' => $getRecord[0]->name,
                    'provider' => [
                        'id' => strval($getRecord[0]->provider_id)
                    ],
                    'payment_system' => [
                        'id' => strval($getRecord[0]->payment_system_id)
                    ],
                    'commission_template' => [
                        'id' => strval($getRecord[0]->commission_template_id)
                    ],
                ]
            ];

        $this->graphQL('
        {
             commissionPriceLists(where: { column: PAYMENT_SYSTEM_ID, value: 1 }) {
                data {
                    id
                    name
                    provider {
                        id
                    }
                    payment_system {
                        id
                    }
                    commission_template {
                        id
                    }
                }
             }
        }
        ')->seeJsonContains($data);
    }

    public function testCreateCommissionPriceList()
    {
        $this->graphQL('
            mutation (
                $name: String!
                $provider_id: ID!
                $payment_system_id: ID!
                $commission_template_id: ID!
            ) {
            createCommissionPriceList(
                name: $name
                provider_id: $provider_id
                payment_system_id: $payment_system_id
                commission_template_id: $commission_template_id
            ) {
                id
            }
            }
        ', [
            'name' => 'Test Commission Price List',
            'provider_id' => 1,
            'payment_system_id' => 1,
            'commission_template_id' => 1
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createCommissionPriceList' => [
                    'id' => $id['data']['createCommissionPriceList']['id'],
                ],
            ],
        ]);
    }

    public function testUpdateCommissionPriceList()
    {
        $commissionPriceList = CommissionPriceList::factory()->create();

        $this->graphQL('
            mutation (
                $id: ID!
                $name: String!
                $provider_id: ID!
                $payment_system_id: ID!
                $commission_template_id: ID!
            ) {
            updateCommissionPriceList(
                id: $id
                name: $name
                provider_id: $provider_id
                payment_system_id: $payment_system_id
                commission_template_id: $commission_template_id
            ) {
                id
                name
            }
            }
        ', [
            'id' => strval($commissionPriceList->id),
            'name' => 'Updated Commission Price List',
            'provider_id' => 1,
            'payment_system_id' => 1,
            'commission_template_id' => 1
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updateCommissionPriceList' => [
                    'id' => $id['data']['updateCommissionPriceList']['id'],
                    'name' => $id['data']['updateCommissionPriceList']['name'],
                ],
            ],
        ]);
    }

    public function testDeleteCommissionPriceList()
    {
        $commissionPriceList = CommissionPriceList::factory()->create();

        $this->graphQL('
            mutation (
                $id: ID!
            ) {
            deleteCommissionPriceList(
                id: $id
            ) {
                id
            }
            }
        ', [
            'id' => strval($commissionPriceList->id)
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deleteCommissionPriceList' => [
                    'id' => $id['data']['deleteCommissionPriceList']['id'],
                ],
            ],
        ]);
    }
    */
}

