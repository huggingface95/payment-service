<?php

namespace Feature\GraphQL\Mutations;

use App\Models\TransferIncoming;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferExchangeMutationTest extends TestCase
{
    /**
     * TransferIncomings Mutation Testing
     *
     * @return void
     */
    public function testCreateTransferExchangeNoAuth(): void
    {
        $this->graphQL('
            mutation createTransferExchange($from_account: ID!, $to_account: ID!) {
                  createTransferExchange(
                    amount: 10
                    from_account_id: $from_account
                    to_account_id: $to_account
                  )
                {
                    id
                      amount
                      amount_debt
                      fee {
                        fee
                        fee_amount
                      }
                      fees {
                        fee
                        fee_pp
                        fee_amount
                      }
                      files {
                        id
                        file_name
                        mime_type
                      }
                      currency {
                        id
                        name
                      }
                      status {
                        id
                        name
                      }
                      payment_urgency {
                        id
                        name
                      }
                      payment_operation_type {
                        id
                        name
                      }
                      payment_provider {
                        id
                        name
                        description
                      }
                      payment_provider_history {
                        id
                        payment_provider_id
                        transfer_id
                      }
                      payment_system {
                        id
                        name
                      }
                      payment_bank {
                        id
                        name
                        address
                      }
                      payment_number
                      payment_operation_type {
                        id
                        name
                      }
                      transfer_type {
                        id
                        name
                      }
                      account {
                        id
                        account_type
                      }
                      recipient {
                        __typename
                      }
                      company {
                        id
                        name
                        email
                      }
                      system_message
                      reason
                      channel
                      bank_message
                      sender_account
                      sender_bank_name
                      sender_bank_address
                      sender_bank_swift
                      sender_bank_country {
                        id
                        name
                      }
                      sender_name
                      sender_country {
                        id
                        name
                      }
                      sender_city
                      sender_address
                      sender_state
                      sender_zip
                      respondent_fee {
                        id
                        name
                      }
                }
            }
        ', [
                'from_account' => 1,
                'to_account' => 2,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTransferExchange(): void
    {
        $seq = DB::table('transfer_outgoings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_outgoings_id_seq RESTART WITH '.$seq);

        $seq = DB::table('transfer_incomings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_incomings_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                    mutation CreateTransferExchange($from_account: ID!, $to_account: ID!) {
                      createTransferExchange(
                        amount: 10
                        from_account_id: $from_account
                        to_account_id: $to_account
                      ) {
                        id
                        amount
                        amount_debt
                        payment_number
                        system_message
                        reason
                        channel
                        bank_message
                    }
                }
                ',
                    'variables' => [
                        'from_account' => 1,
                        'to_account' => 4,
                    ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createTransferExchange' => [
                    'id' => $id['data']['createTransferExchange']['id'],
                    'amount' => $id['data']['createTransferExchange']['amount'],
                    'amount_debt' => $id['data']['createTransferExchange']['amount_debt'],
                    'payment_number' => $id['data']['createTransferExchange']['payment_number'],
                    'system_message' => $id['data']['createTransferExchange']['system_message'],
                    'reason' => $id['data']['createTransferExchange']['reason'],
                    'channel' => $id['data']['createTransferExchange']['channel'],
                    'bank_message' => $id['data']['createTransferExchange']['bank_message'],
                ],
            ],
        ]);
    }
}
