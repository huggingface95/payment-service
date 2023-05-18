<?php

namespace Feature\GraphQL\Mutations;

use App\Models\TransferIncoming;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferBetweenUsersMutationTest extends TestCase
{
    /**
     * Transfer Between Users Mutation Testing
     *
     * @return void
     */
    public function testCreateTransferBetweenUsersNoAuth(): void
    {
        $this->graphQL('
            mutation createTransferBetweenUsers($from_account: ID!, $to_account: ID!) {
                  createTransferBetweenUsers(
                    amount: 10
                    from_account_id: $from_account
                    to_account_id: $to_account
                    respondent_fee_id: 1
                    price_list_fee_id: 1
                    price_list_id: 1
                  )
                {
                    transfer_incoming {
                        id
                    }
                    transfer_outgoing {
                        id
                        price_list_fee {
                            name
                        }
                    }
                    fee_amount
                    final_amount
                }
            }
        ', [
            'from_account' => 1,
            'to_account' => 2,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTransferBetweenUsers(): void
    {
        $this->markTestSkipped('Skipped');
        $seq = DB::table('transfer_outgoings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_outgoings_id_seq RESTART WITH '.$seq);

        $seq = DB::table('transfer_incomings')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE transfer_incomings_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
                    mutation CreateTransferBetweenUsers($from_account: ID!, $to_account: ID!) {
                      createTransferBetweenUsers(
                        amount: 10
                        from_account_id: $from_account
                        to_account_id: $to_account
                        respondent_fee_id: 1
                        price_list_fee_id: 1
                        price_list_id: 1
                      ) {
                        transfer_incoming {
                            id
                        }
                        transfer_outgoing {
                            id
                            price_list_fee {
                                name
                            }
                        }
                        fee_amount
                        final_amount
                      }
                    }
                ',
                'variables' => [
                    'from_account' => 1,
                    'to_account' => 2,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $response = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createTransferBetweenUsers' => [
                    'transfer_incoming' => [
                        'id' => $response['data']['createTransferBetweenUsers']['transfer_incoming']['id'],
                    ],
                    'transfer_outgoing' => [
                        'id' => $response['data']['createTransferBetweenUsers']['transfer_outgoing']['id'],
                        'price_list_fee' => [
                            'name' => $response['data']['createTransferBetweenUsers']['transfer_outgoing']['price_list_fee']['name'],
                        ],
                    ],
                    'fee_amount' => $response['data']['createTransferBetweenUsers']['fee_amount'],
                    'final_amount' => $response['data']['createTransferBetweenUsers']['final_amount'],
                ],
            ],
        ]);
    }

    public function testSignTransferBetweenUsers(): void
    {
        $this->markTestSkipped('Skipped');
        $transfer = TransferIncoming::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation SignTransferBetweenUsers($transfer: ID!, $code: String!) {
                      signTransferBetweenUsers(
                        transfer_incoming_id: $transfer
                        code: $code
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
                    'transfer' => $transfer->id,
                    'code' => '658999',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'signTransferBetweenUsers' => [
                    'id' => $id['data']['signTransferBetweenUsers']['id'],
                    'amount' => $id['data']['signTransferBetweenUsers']['amount'],
                    'amount_debt' => $id['data']['signTransferBetweenUsers']['amount_debt'],
                    'payment_number' => $id['data']['signTransferBetweenUsers']['payment_number'],
                    'system_message' => $id['data']['signTransferBetweenUsers']['system_message'],
                    'reason' => $id['data']['signTransferBetweenUsers']['reason'],
                    'channel' => $id['data']['signTransferBetweenUsers']['channel'],
                    'bank_message' => $id['data']['signTransferBetweenUsers']['bank_message'],
                ],
            ],
        ]);
    }

    public function testExecuteTransferBetweenUsers(): void
    {
        $this->markTestSkipped('Skipped');
        $transfer = TransferIncoming::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation ExecuteTransferBetweenUsers($transfer: ID!) {
                      executeTransferBetweenUsers(
                        transfer_incoming_id: $transfer
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
                    'transfer' => $transfer->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'executeTransferBetweenUsers' => [
                    'id' => $id['data']['executeTransferBetweenUsers']['id'],
                    'amount' => $id['data']['executeTransferBetweenUsers']['amount'],
                    'amount_debt' => $id['data']['executeTransferBetweenUsers']['amount_debt'],
                    'payment_number' => $id['data']['executeTransferBetweenUsers']['payment_number'],
                    'system_message' => $id['data']['executeTransferBetweenUsers']['system_message'],
                    'reason' => $id['data']['executeTransferBetweenUsers']['reason'],
                    'channel' => $id['data']['executeTransferBetweenUsers']['channel'],
                    'bank_message' => $id['data']['executeTransferBetweenUsers']['bank_message'],
                ],
            ],
        ]);
    }
}
