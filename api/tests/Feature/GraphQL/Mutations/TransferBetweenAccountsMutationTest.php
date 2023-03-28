<?php

namespace Feature\GraphQL\Mutations;

use App\Models\Account;
use App\Models\TransferIncoming;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransferBetweenAccountsMutationTest extends TestCase
{
    /**
     * TransferIncomings Mutation Testing
     *
     * @return void
     */
    public function testCreateTransferBetweenAccountsNoAuth(): void
    {
        $this->graphQL('
            mutation createTransferBetweenAccounts($from_account: ID!, $to_account: ID!) {
                  createTransferBetweenAccounts(
                    amount: 10
                    from_account_id: $from_account
                    to_account_id: $to_account
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
                }
            }
        ', [
                'from_account' => 1,
                'to_account' => 2,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTransferBetweenAccounts(): void
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
                    mutation CreateTransferBetweenAccounts($from_account: ID!, $to_account: ID!) {
                      createTransferBetweenAccounts(
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
                        'to_account' => 2,
                    ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        $this->seeJson([
            'data' => [
                'createTransferBetweenAccounts' => [
                    'id' => $id['data']['createTransferBetweenAccounts']['id'],
                    'amount' => $id['data']['createTransferBetweenAccounts']['amount'],
                    'amount_debt' => $id['data']['createTransferBetweenAccounts']['amount_debt'],
                    'payment_number' => $id['data']['createTransferBetweenAccounts']['payment_number'],
                    'system_message' => $id['data']['createTransferBetweenAccounts']['system_message'],
                    'reason' => $id['data']['createTransferBetweenAccounts']['reason'],
                    'channel' => $id['data']['createTransferBetweenAccounts']['channel'],
                    'bank_message' => $id['data']['createTransferBetweenAccounts']['bank_message'],
                ],
            ],
        ]);
    }

    public function testSignTransferBetweenAccounts(): void
    {
        $transfer = TransferIncoming::orderBy('id', 'DESC')->first();

        $this->postGraphQL(
            [
                'query' => '
                    mutation SignTransferBetweenAccounts($transfer: ID!) {
                      signTransferBetweenAccounts(
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
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        );

        $id = json_decode($this->response->getContent(), true);

        dump($id);

        $this->seeJson([
            'data' => [
                'signTransferBetweenAccounts' => [
                    'id' => $id['data']['signTransferBetweenAccounts']['id'],
                    'amount' => $id['data']['signTransferBetweenAccounts']['amount'],
                    'amount_debt' => $id['data']['signTransferBetweenAccounts']['amount_debt'],
                    'payment_number' => $id['data']['signTransferBetweenAccounts']['payment_number'],
                    'system_message' => $id['data']['signTransferBetweenAccounts']['system_message'],
                    'reason' => $id['data']['signTransferBetweenAccounts']['reason'],
                    'channel' => $id['data']['signTransferBetweenAccounts']['channel'],
                    'bank_message' => $id['data']['signTransferBetweenAccounts']['bank_message'],
                ],
            ],
        ]);
    }
}
