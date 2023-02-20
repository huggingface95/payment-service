<?php

namespace Feature\GraphQL\Mutations;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TicketsMutationTest extends TestCase
{
    /**
     * Tickets Mutation Testing
     *
     * @return void
     */
    public function testCreateTicketNoAuth(): void
    {
        $seq = DB::table('tickets')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE tickets_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateTicket(
                $title: String!,
                $message: String!,
                $member_id: ID!,
                $client_id: ID!,
            ) {
                createTicket(
                    title: $title,
                    message: $message,
                    member_id: $member_id,
                    client_id: $client_id,
                ) {
                    id
                }
            }
        ', [
            'title' => 'New Ticket',
            'message' => 'System Error',
            'member_id' => 2,
            'client_id' => 2,
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateTicket(): void
    {
        $seq = DB::table('tickets')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE tickets_id_seq RESTART WITH '.$seq);

        $this->postGraphQL([
            'query' => '
                mutation CreateTicket(
                    $title: String!,
                    $message: String!,
                    $member_id: ID!,
                    $client_id: ID!,
                ) {
                    createTicket(
                        title: $title,
                        message: $message,
                        member_id: $member_id,
                        client_id: $client_id,
                    ) {
                        id
                    }
                }',
            'variables' => [
                'title' => 'New Ticket',
                'message' => 'System Error',
                'member_id' => 2,
                'client_id' => 2,
            ]
        ],
        [
            'Authorization' => 'Bearer ' . $this->login(),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createTicket']['id'],
            ],
        ]);
    }

    public function testUpdateTicket(): void
    {
        $this->postGraphQL([
            'query' => '
                mutation UpdateTicket(
                    $id: ID!,
                    $message: String,
                ) {
                    updateTicket(
                        id: $id,
                        message: $message,
                    ) {
                        id
                    }
                }',
            'variables' => [
                'id' => 1,
                'message' => 'Updated Ticket Message',
            ]
        ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['updateTicket']['id'],
            ],
        ]);
    }
}
