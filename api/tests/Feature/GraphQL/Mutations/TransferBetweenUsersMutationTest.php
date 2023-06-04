<?php

namespace Feature\GraphQL\Mutations;

use App\Models\TransferBetween;
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
                    'from_account' => 4,
                    'to_account' => 3,
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
        $transfer = TransferBetween::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation SignTransferBetweenUsers($transfer: ID!, $code: String!) {
                      signTransferBetweenUsers(
                        id: $transfer
                        code: $code
                      ) {
                        id
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
                ],
            ],
        ]);
    }

    public function testExecuteTransferBetweenUsers(): void
    {
        $transfer = TransferBetween::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation ExecuteTransferBetweenUsers($transfer: ID!) {
                      executeTransferBetweenUsers(
                        id: $transfer
                      ) {
                        id
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
                ],
            ],
        ]);
    }

    public function testAttachFilesToTransferBetweenUsers(): void
    {
        $transfer = TransferBetween::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation AttachFilesToBetweenUsers($transfer: ID!, $file: [ID!]!) {
                      attachFIleToTransferBetweenUsers(
                        id: $transfer
                        file_id: $file
                      ) {
                        id
                      }
                    }
                ',
                'variables' => [
                    'transfer' => $transfer->id,
                    'file' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'attachFIleToTransferBetweenUsers' => [
                    'id' => $id['data']['attachFIleToTransferBetweenUsers']['id'],
                ],
            ],
        ]);
    }
}
