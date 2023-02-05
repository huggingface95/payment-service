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
            ->table((new ActiveSession)->getTable())
            ->select(['id', 'company'])
            ->limit(10)
            ->orderBy('created_at', 'DESC')
            ->get();

        $response = $this->postGraphQL([
            'query' =>
            '{
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
        '],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

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
            ->table((new ActiveSession)->getTable())
            ->limit(1)
            ->get();
        
        $active_sessions = DB::connection('clickhouse')
            ->table((new ActiveSession)->getTable())
            ->where('company', $active_session[0]['company'])
            ->where('provider', $active_session[0]['provider'])
            ->where('created_at', $active_session[0]['created_at'])
            ->get();

        $created_at = substr($active_session[0]['created_at'], 0, 10);

        $response = $this->postGraphQL([
            'query' =>
                'query($company: String!, $provider: String!, $created_at: Date!) {
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
                'created_at' => $created_at,

            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        foreach ($active_sessions as $session) {
            $response->seeJsonContains([
                'id' => (string) $session['id'],
                'company' => (string) $session['company'],
            ]);
        }
    }

    public function testActiveSessionsListPaginate(): void
    {
        $response = $this->postGraphQL([
            'query' => '
                {
                    activeSessions(page: 1, count: 3) {
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
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ]);

        $response->seeJson([
            'count' => 3,
            'currentPage' => 1,
            'firstItem' => 1,
            'hasMorePages' => true,
            'lastItem' => 3,
            'lastPage' => 10,
            'perPage' => 3,
            'total' => 30,
        ]);
    }
}
