<?php

namespace Feature\GraphQL\Queries;

use App\Models\Ticket;
use Tests\TestCase;

class TicketsQueryTest extends TestCase
{
    public function testQueryTicketsNoAuth(): void
    {
        $this->graphQL('
            {
                tickets {
                    data {
                        id
                        title
                        message
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryTicket(): void
    {
        $ticket = Ticket::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query Ticket($id: ID) {
                    ticket(id: $id) {
                            id
                            title
                            message
                    }
                }',
                'variables' => [
                    'id' => $ticket->id,
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJson([
            'data' => [
                'ticket' => [
                        'id' => (string) $ticket->id,
                        'title' => (string) $ticket->title,
                        'message' => (string) $ticket->message,
                    ],
                ],
        ]);
    }

    public function testQueryTicketsList(): void
    {
        $tickets = Ticket::orderBy('id', 'ASC')->get();

        $this->postGraphQL(
            [
                'query' => '
                {
                    tickets (orderBy: { column: ID, order: ASC }) {
                        data {
                            id
                            title
                            message
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains([
            'id' => (string) $tickets[0]->id,
            'title' => (string) $tickets[0]->title,
            'message' => (string) $tickets[0]->message,
        ]);
    }

    public function testQueryTicketsWithFilterByCompanyId(): void
    {
        $tickets = Ticket::orderBy('id', 'ASC')
            ->first();

        $company = $tickets->company()->first();

        $data = [
            'id' => (string) $tickets->id,
            'title' => (string) $tickets->title,
            'message' => (string) $tickets->message,
        ];

        $this->postGraphQL(
            [
                'query' => 'query Tickets($id: Mixed) {
                    tickets (
                        filter: { column: HAS_COMPANY_FILTER_BY_ID, value: $id }
                    ) {
                        data {
                            id
                            title
                            message
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $company->id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    /**
     * @dataProvider provide_testQueryTicketsWithFilterByCondition
     */
    public function testQueryTicketsWithFilterByCondition($cond, $value): void
    {
        $tickets = Ticket::where($cond, $value)
            ->orderBy('id', 'ASC')
            ->get();

        $data = [
            'data' => [
                'tickets' => [],
            ],
        ];

        foreach ($tickets as $ticket) {
            $data['data']['tickets']['data'][] = [
                'id' => (string) $ticket->id,
                'title' => (string) $ticket->title,
                'message' => (string) $ticket->message,
            ];
        }

        $this->postGraphQL(
            [
                'query' => 'query Tickets($id: Mixed) {
                    tickets (
                        filter: { column: '.strtoupper($cond).', operator: EQ, value: $id }
                    ) {
                        data {
                            id
                            title
                            message
                        }
                    }
                }',
                'variables' => [
                    'id' => $value,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->login(),
            ]
        )->seeJsonContains($data);
    }

    public function provide_testQueryTicketsWithFilterByCondition()
    {
        return [
            ['id', '1'],
            ['member_id', '1'],
            ['client_id', '1'],
        ];
    }
}
