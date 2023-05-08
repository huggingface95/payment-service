<?php

namespace Feature\GraphQL\Mutations;

use App\Models\TransferOutgoing;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferOutgoingsMutationTest extends TestCase
{
    /**
     * TransferOutgoings Mutation Testing
     *
     * @return void
     */
    public function testCreateTransferOutgoingNoAuth(): void
    {
        $this->graphQL('
            mutation CreateTransferOutgoing(
                $group_id: ID!
                $group_type_id: ID!
                $project_id: ID!
                $account_id: ID!
                $payment_provider_id: ID!
                $payment_system_id: ID!
                $urgency_id: ID!
                $recipient_account: String
                $recipient_bank_name: String
                $recipient_bank_address: String
                $recipient_bank_swift: String
                $recipient_name: String
                $recipient_city: String
                $recipient_address: String
                $recipient_state: String
                $recipient_zip: String
                $respondent_fees_id: ID!
                $reason: String!
                $bank_message: String
            )
            {
                createTransferOutgoing (
                    group_id: $group_id
                    group_type_id: $group_type_id
                    project_id: $project_id
                    amount: 10
                    account_id: $account_id
                    payment_provider_id: $payment_provider_id
                    payment_system_id: $payment_system_id
                    urgency_id: $urgency_id
                    recipient_account: $recipient_account
                    recipient_bank_name: $recipient_bank_name
                    recipient_bank_address: $recipient_bank_address
                    recipient_bank_swift: $recipient_bank_swift
                    recipient_name: $recipient_name
                    recipient_city: $recipient_city
                    recipient_address: $recipient_address
                    recipient_state: $recipient_state
                    recipient_zip: $recipient_zip
                    respondent_fees_id: $respondent_fees_id
                    reason: $reason
                    bank_message: $bank_message
                )
                {
                    id
                      amount
                      amount_debt
                      fee_amount
                      fee {
                        fee
                      }
                      fees {
                        fee
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
                      company {
                        id
                        name
                        email
                      }
                      system_message
                      reason
                      channel
                      bank_message
                      recipient_account
                      recipient_bank_name
                      recipient_bank_address
                      recipient_bank_swift
                      recipient_bank_country {
                        id
                        name
                      }
                      recipient_name
                      recipient_country {
                        id
                        name
                      }
                      recipient_city
                      recipient_address
                      recipient_state
                      recipient_zip
                      respondent_fee {
                        id
                        name
                      }
                }
            }
        ', [
                'group_id' => 1,
                'group_type_id' => 1,
                'project_id' => 1,
                'account_id' => 1,
                'payment_provider_id' => 1,
                'payment_system_id' => 1,
                'urgency_id' => 1,
                'recipient_account' => 'Sender Account',
                'recipient_bank_name' => 'recipient_bank_name',
                'recipient_bank_address' => 'recipient_bank_address',
                'recipient_bank_swift' => 'recipient_bank_swift',
                'recipient_name' => 'recipient_name',
                'recipient_city' => 'recipient_city',
                'recipient_address' => 'recipient_address',
                'recipient_state' => 'recipient_state',
                'recipient_zip' => 'recipient_zip',
                'respondent_fees_id' => 2,
                'reason' => 'Test reason',
                'bank_message' => 'bank_message',
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTransferOutgoing(): void
    {
        $seq = DB::table('transfer_outgoings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_outgoings_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                mutation CreateTransferOutgoing(
                $group_id: ID!
                $group_type_id: ID!
                $project_id: ID!
                $account_id: ID!
                $payment_provider_id: ID!
                $payment_system_id: ID!
                $urgency_id: ID!
                $recipient_account: String
                $recipient_bank_name: String
                $recipient_bank_address: String
                $recipient_bank_swift: String
                $recipient_name: String
                $recipient_city: String
                $recipient_address: String
                $recipient_state: String
                $recipient_zip: String
                $respondent_fees_id: ID!
                $reason: String!
                $bank_message: String
            )
            {
                createTransferOutgoing (
                    group_id: $group_id
                    group_type_id: $group_type_id
                    project_id: $project_id
                    amount: 10
                    account_id: $account_id
                    payment_provider_id: $payment_provider_id
                    payment_system_id: $payment_system_id
                    urgency_id: $urgency_id
                    recipient_account: $recipient_account
                    recipient_bank_name: $recipient_bank_name
                    recipient_bank_address: $recipient_bank_address
                    recipient_bank_swift: $recipient_bank_swift
                    recipient_name: $recipient_name
                    recipient_city: $recipient_city
                    recipient_address: $recipient_address
                    recipient_state: $recipient_state
                    recipient_zip: $recipient_zip
                    respondent_fees_id: $respondent_fees_id
                    reason: $reason
                    bank_message: $bank_message
                )
                {
                      id
                      amount
                      amount_debt
                      payment_number
                      system_message
                      reason
                      channel
                      bank_message
                      recipient_account
                      recipient_bank_name
                      recipient_bank_address
                      recipient_bank_swift
                      recipient_name
                      recipient_city
                      recipient_address
                      recipient_state
                      recipient_zip
                }
                }',
                'variables' => [
                    'group_id' => 1,
                    'group_type_id' => 1,
                    'project_id' => 1,
                    'account_id' => 1,
                    'payment_provider_id' => 1,
                    'payment_system_id' => 1,
                    'urgency_id' => 1,
                    'recipient_account' => 'Sender Account',
                    'recipient_bank_name' => 'recipient_bank_name',
                    'recipient_bank_address' => 'recipient_bank_address',
                    'recipient_bank_swift' => 'recipient_bank_swift',
                    'recipient_name' => 'recipient_name',
                    'recipient_city' => 'recipient_city',
                    'recipient_address' => 'recipient_address',
                    'recipient_state' => 'recipient_state',
                    'recipient_zip' => 'recipient_zip',
                    'respondent_fees_id' => 2,
                    'reason' => 'Test reason',
                    'bank_message' => 'bank_message',
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createTransferOutgoing' => [
                    'id' => $id['data']['createTransferOutgoing']['id'],
                    'amount' => $id['data']['createTransferOutgoing']['amount'],
                    'amount_debt' => $id['data']['createTransferOutgoing']['amount_debt'],
                    'payment_number' => $id['data']['createTransferOutgoing']['payment_number'],
                    'system_message' => $id['data']['createTransferOutgoing']['system_message'],
                    'reason' => $id['data']['createTransferOutgoing']['reason'],
                    'channel' => $id['data']['createTransferOutgoing']['channel'],
                    'bank_message' => $id['data']['createTransferOutgoing']['bank_message'],
                    'recipient_account' => $id['data']['createTransferOutgoing']['recipient_account'],
                    'recipient_bank_name' => $id['data']['createTransferOutgoing']['recipient_bank_name'],
                    'recipient_bank_address' => $id['data']['createTransferOutgoing']['recipient_bank_address'],
                    'recipient_bank_swift' => $id['data']['createTransferOutgoing']['recipient_bank_swift'],
                    'recipient_name' => $id['data']['createTransferOutgoing']['recipient_name'],
                    'recipient_city' => $id['data']['createTransferOutgoing']['recipient_city'],
                    'recipient_address' => $id['data']['createTransferOutgoing']['recipient_address'],
                    'recipient_state' => $id['data']['createTransferOutgoing']['recipient_state'],
                    'recipient_zip' => $id['data']['createTransferOutgoing']['recipient_zip'],
                ],
            ],
        ]);
    }

    public function testUpdateTransferOutgoing(): void
    {
        $TransferOutgoing = TransferOutgoing::orderBy('id', 'DESC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                mutation UpdateTransferOutgoing(
                $id: ID!
            )
            {
                updateTransferOutgoing (
                    id: $id
                    status_id: 1
                )
                {
                      id
                      amount
                      amount_debt
                      payment_number
                      system_message
                      reason
                      channel
                      bank_message
                      recipient_account
                      recipient_bank_name
                      recipient_bank_address
                      recipient_bank_swift
                      recipient_name
                      recipient_city
                      recipient_address
                      recipient_state
                      recipient_zip
                }
                }',
                'variables' => [
                    'id' => $TransferOutgoing->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'updateTransferOutgoing' => [
                    'id' => $id['data']['updateTransferOutgoing']['id'],
                    'amount' => $id['data']['updateTransferOutgoing']['amount'],
                    'amount_debt' => $id['data']['updateTransferOutgoing']['amount_debt'],
                    'payment_number' => $id['data']['updateTransferOutgoing']['payment_number'],
                    'system_message' => $id['data']['updateTransferOutgoing']['system_message'],
                    'reason' => $id['data']['updateTransferOutgoing']['reason'],
                    'channel' => $id['data']['updateTransferOutgoing']['channel'],
                    'bank_message' => $id['data']['updateTransferOutgoing']['bank_message'],
                    'recipient_account' => $id['data']['updateTransferOutgoing']['recipient_account'],
                    'recipient_bank_name' => $id['data']['updateTransferOutgoing']['recipient_bank_name'],
                    'recipient_bank_address' => $id['data']['updateTransferOutgoing']['recipient_bank_address'],
                    'recipient_bank_swift' => $id['data']['updateTransferOutgoing']['recipient_bank_swift'],
                    'recipient_name' => $id['data']['updateTransferOutgoing']['recipient_name'],
                    'recipient_city' => $id['data']['updateTransferOutgoing']['recipient_city'],
                    'recipient_address' => $id['data']['updateTransferOutgoing']['recipient_address'],
                    'recipient_state' => $id['data']['updateTransferOutgoing']['recipient_state'],
                    'recipient_zip' => $id['data']['updateTransferOutgoing']['recipient_zip'],
                ],
            ],
        ]);
    }
}
