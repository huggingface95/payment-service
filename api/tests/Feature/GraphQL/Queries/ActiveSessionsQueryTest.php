<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\Clickhouse\ActiveSession;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ActiveSessionsQueryTest extends TestCase
{
    public function testActiveSessionsNoAuth(): void
    {
        $this->graphQL('
            {
                activeSessions {
                    data {
                        id
                        company
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testActiveSessionsList(): void
    {
        $active_sessions = DB::connection('clickhouse')
            ->table((new ActiveSession())->getTable())
            ->select(['id', 'company'])
            ->limit(10)
            ->orderBy('created_at', 'DESC')
            ->get();

        $response = $this->postGraphQL(
            [
                'query' => '{
                activeSessions {
                    data {
                        id
                        company
                    }
                    paginatorInfo {
                        count
                        currentPage
                        firstItem
                        hasMorePages
                        lastItem
                        lastPage
                        perPage
                        total
                    }
                }
            }
        ', ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        foreach ($active_sessions as $session) {
            $response->seeJsonContains([
                'id' => (string) $session['id'],
                'company' => (string) $session['company'],
            ]);
        }
    }

    public function testActiveSessionsListWithQuery(): void
    {
        $active_session = DB::connection('clickhouse')
            ->table((new ActiveSession())->getTable())
            ->limit(1)
            ->get();

        $active_sessions = DB::connection('clickhouse')
            ->table((new ActiveSession())->getTable())
            ->where('company', $active_session[0]['company'])
            ->where('provider', $active_session[0]['provider'])
            ->where('created_at', $active_session[0]['created_at'])
            ->get();


        $response = $this->postGraphQL(
            [
                'query' => 'query($company: String!, $provider: String!, $created_at: DateTimeRange!) {
                    activeSessions(
                        query: {
                            company: $company
                            provider: $provider
                            created_at: $created_at
                        }
                    ) {
                        data {
                            id
                            company
                        }
                        paginatorInfo {
                            count
                            currentPage
                            firstItem
                            hasMorePages
                            lastItem
                            lastPage
                            perPage
                            total
                        }
                    }
                }',
                'variables' => [
                    'company' => $active_session[0]['company'],
                    'provider' => $active_session[0]['provider'],
                    'created_at' => $active_session[0]['created_at'],

                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        foreach ($active_sessions as $session) {
            $response->seeJsonContains([
                'id' => (string) $session['id'],
                'company' => (string) $session['company'],
            ]);
        }
    }

    public function testActiveSessionsListPaginate(): void
    {
        $this->postGraphQL(
            [
                'query' => '
                {
                    activeSessions(page: 1, count: 3) {
                      paginatorInfo {
                        count
                        currentPage
                        firstItem
                        hasMorePages
                        lastItem
                        lastPage
                        perPage
                        total
                      }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $response = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            'count' => $response['data']['activeSessions']['paginatorInfo']['count'],
        ]);
    }
}
