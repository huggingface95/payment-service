<?php

class PaymentsTest extends TestCase
{
    /**
     * Payments Testing
     *
     * @return void
     */
    public function login()
    {
        auth()->attempt(['email' => 'test@test.com', 'password' => '1234567Qa']);
    }

    public function testCreatePayment()
    {
        $this->login();
        $this->graphQL('
            mutation CreatePayment(
                $fee_type_id: ID!
                $currency_id: ID!
                $status_id: ID!
                $sender_name: String!
                $payment_details: String!
                $sender_bank_account: String!
                $sender_bank_name: String!
                $sender_swift: String
                $sender_bank_country: ID
                $sender_bank_address: String
                $sender_address: String
                $sender_country_id: ID
                $urgency_id: ID!
                $type_id: ID!
                $payment_provider_id: ID!
                $account_id: ID!
                $company_id: ID!
                $payment_number: String!
                $error: String
                $received_at: DateTime
            )
            {
                createPayment (
                    amount: 5000.00
                    amount_real: 5000.00
                    fee_type_id: $fee_type_id
                    currency_id: $currency_id
                    status_id: $status_id
                    sender_name: $sender_name
                    payment_details: $payment_details
                    sender_bank_account: $sender_bank_account
                    sender_bank_name: $sender_bank_name
                    sender_swift: $sender_swift
                    sender_bank_country: $sender_bank_country
                    sender_bank_address: $sender_bank_address
                    sender_address: $sender_address
                    sender_country_id: $sender_country_id
                    urgency_id: $urgency_id
                    type_id: $type_id
                    payment_provider_id: $payment_provider_id
                    account_id: $account_id
                    company_id: $company_id
                    payment_number: $payment_number
                    error: $error
                    received_at: $received_at
                )
                {
                    id
                }
            }
        ', [
            'fee_type_id' =>  1,
            'currency_id' => 2,
            'status_id' => 2,
            'sender_name' => 'TestSender',
            'payment_details' => 'Payment Test Details',
            'sender_bank_account' => '1561651651651',
            'sender_bank_name' => 'Sender Bank Name',
            'sender_swift' => 'Sender Test Swift',
            'sender_bank_country' => 1,
            'sender_bank_address' => '1st street',
            'sender_address' => '2nd street',
            'sender_country_id' => 1,
            'urgency_id' => 1,
            'type_id' => 2,
            'payment_provider_id' => 1,
            'account_id' => 1,
            'company_id' => 1,
            'payment_number' => '45645646545646',
            'error' => 'no error',
            'received_at' => \Illuminate\Support\Carbon::now(),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'createPayment' => [
                    'id' => $id['data']['createPayment']['id'],
                ],
            ],
        ]);
    }

    public function testUpdatePayment()
    {
        $this->login();
        $payment = \App\Models\Payments::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation UpdatePayment(
                $id: ID!
                $urgency_id: ID!
                $type_id: ID!
                $sender_name: String
            )
            {
                updatePayment (
                    id: $id
                    urgency_id: $urgency_id
                    type_id: $type_id
                    sender_name: $sender_name
                )
                {
                    id
                    sender_name
                }
            }
        ', [
            'id' => strval($payment[0]->id),
            'urgency_id' => 1,
            'type_id' => 2,
            'sender_name' => 'Changed Sender Name',
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'updatePayment' => [
                    'id' => $id['data']['updatePayment']['id'],
                    'sender_name' => $id['data']['updatePayment']['sender_name'],
                ],
            ],
        ]);
    }

    public function testQueryPayment()
    {
        $this->login();
        $payment = \App\Models\Payments::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            query Payment($id:ID!){
                payment(id: $id) {
                    id
                }
            }
        ', [
            'id' => strval($payment[0]->id),
        ])->seeJson([
            'data' => [
                'payment' => [
                    'id' => strval($payment[0]->id),
                ],
            ],
        ]);
    }

    public function testQueryPaymentOrderBy()
    {
        $this->login();
        $payment = \App\Models\Payments::orderBy('id', 'DESC')->get();
        $this->graphQL('
        query {
            payments(orderBy: { column: ID, order: DESC }) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($payment[0]->id),
            ],
        ]);
    }

    public function testQueryPaymentWhere()
    {
        $this->login();
        $payment = \App\Models\Payments::where('type_id', 2)->get();
        $this->graphQL('
        query {
            payments(where: { column: TYPE_ID, value: 2}) {
                data {
                    id
                }
                }
        }')->seeJsonContains([
            [
                'id' => strval($payment[0]->id),
            ],
        ]);
    }

    public function testDeletePayment()
    {
        $this->login();
        $payment = \App\Models\Payments::orderBy('id', 'DESC')->take(1)->get();
        $this->graphQL('
            mutation DeletePayment(
                $id: ID!
            )
            {
                deletePayment (
                    id: $id
                )
                {
                    id
                }
            }
        ', [
            'id' => strval($payment[0]->id),
        ]);
        $id = json_decode($this->response->getContent(), true);
        $this->seeJson([
            'data' => [
                'deletePayment' => [
                    'id' => $id['data']['deletePayment']['id'],
                ],
            ],
        ]);
    }
}
