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
        $this->login();

        $active_sessions = DB::connection('clickhouse')
            ->table((new ActiveSession)->getTable())
            ->select(['id', 'company'])
            ->limit(10)
            ->orderBy('id', 'DESC')
            ->get();

        $response = $this->graphQL('
            {
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
        ');

        foreach ($active_sessions as $session) {
            $response->seeJson([
                'id' => (string) $session['id'],
                'company' => (string) $session['company'],
            ]);
        }

    }

    public function testActiveSessionsListWithQuery(): void
    {
        $this->login();

        $active_session = DB::connection('clickhouse')
            ->table((new ActiveSession)->getTable())
            ->limit(1)
            ->get();

        $active_sessions = DB::connection('clickhouse')
            ->table((new ActiveSession)->getTable())
            ->where('company', $active_session[0]['company'])
            ->where('member', $active_session[0]['member'])
            ->where('group', $active_session[0]['group'])
            ->where('created_at', $active_session[0]['created_at'])
            ->get();

        $created_at = substr($active_session[0]['created_at'], 0, 10);

        $response = $this->graphQL('
        query($company: String!, $member: String!, $group: String!, $created_at: Date!) {
            activeSessions(
                query: {
                    company: $company
                    member: $member
                    group: $group
                    created_at: $created_at
                }
            ) {
                data {
                    id
                    company
                    member
                    group
                    created_at
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
        ', [
            'company' => $active_session[0]['company'],
            'member' => $active_session[0]['member'],
            'group' => $active_session[0]['group'],
            'created_at' => $created_at,

        ]);

        foreach ($active_sessions as $session) {
            $response->seeJson([
                'id' => (string) $session['id'],
                'company' => (string) $session['company'],
            ]);
        }
    }

    public function testActiveSessionsListPaginate(): void
    {
        $this->login();

        $response = $this->graphQL('
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
        }
        ');

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
