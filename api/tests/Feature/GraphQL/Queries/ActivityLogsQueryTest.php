<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\Clickhouse\ActivityLog;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ActivityLogsQueryTest extends TestCase
{
    public function testActivityLogsNoAuth(): void
    {
        $this->graphQL('
            {
                activityLogs {
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

    public function testActivityLogsList(): void
    {
        $activity_logs = DB::connection('clickhouse')
            ->table((new ActivityLog())->getTable())
            ->select(['id', 'company'])
            ->limit(10)
            ->orderBy('id', 'DESC')
            ->get();

        $response = $this->postGraphQL(
            [
                'query' => '{
                    activityLogs {
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
            ',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        foreach ($activity_logs as $activity_log) {
            $response->seeJson([
                'id' => (string) $activity_log['id'],
                'company' => (string) $activity_log['company'],
            ]);
        }
    }

    public function testActivityLogsListWithQuery(): void
    {
        $this->markTestSkipped('Skipped');
        $activity_log = DB::connection('clickhouse')
            ->table((new ActivityLog())->getTable())
            ->limit(1)
            ->first();

        $activity_logs = DB::connection('clickhouse')
            ->table((new ActivityLog())->getTable())
            ->where('company', $activity_log['company'])
            ->where('member', $activity_log['member'])
            ->where('group', $activity_log['group'])
            ->where('domain', $activity_log['domain'])
            ->where('created_at', $activity_log['created_at'])
            ->get();

        $created_at = substr($activity_log['created_at'], 0, 10);

        $response = $this->postGraphQL(
            [
                'query' => '
                query(
                    $company: String!, $member: String!, $group: String!, $domain: String!, $created_at: Date!
                ) {
                    activityLogs(
                        query: {
                            company: $company
                            member: $member
                            group: $group
                            domain: $domain
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
                    'company' => $activity_log['company'],
                    'member' => $activity_log['member'],
                    'group' => $activity_log['group'],
                    'domain' => $activity_log['domain'],
                    'created_at' => $created_at,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        foreach ($activity_logs as $activity_log) {
            $response->seeJson([
                'id' => (string) $activity_log['id'],
                'company' => (string) $activity_log['company'],
            ]);
        }
    }

    public function testActivityLogsListPaginate(): void
    {
        $response = $this->postGraphQL(
            [
                'query' => '
                {
                    activityLogs(page: 1, count: 3) {
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
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        $response = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            'count' => $response['data']['activityLogs']['paginatorInfo']['count'],
        ]);
    }
}
