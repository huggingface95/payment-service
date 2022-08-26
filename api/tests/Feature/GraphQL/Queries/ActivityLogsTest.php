<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\Clickhouse\ActivityLog;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ActivityLogsTest extends TestCase
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
        $this->login();

        $activity_logs = DB::connection('clickhouse')
            ->table((new ActivityLog)->getTable())
            ->select(['id', 'company'])
            ->limit(10)
            ->orderBy('id', 'DESC')
            ->get();

        $response = $this->graphQL('
            {
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
        ');

        foreach ($activity_logs as $activity_log) {
            $response->seeJson([
                'id' => (string) $activity_log['id'],
                'company' => (string) $activity_log['company'],
            ]);
        }
    }

    public function testActivityLogsListWithQuery(): void
    {
        $this->login();

        $activity_log = DB::connection('clickhouse')
            ->table((new ActivityLog)->getTable())
            ->limit(1)
            ->first();

        $activity_logs = DB::connection('clickhouse')
            ->table((new ActivityLog)->getTable())
            ->where('company', $activity_log['company'])
            ->where('member', $activity_log['member'])
            ->where('group', $activity_log['group'])
            ->where('domain', $activity_log['domain'])
            ->where('created_at', $activity_log['created_at'])
            ->get();

        $created_at = substr($activity_log['created_at'], 0, 10);

        $response = $this->graphQL('
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
            'company' => $activity_log['company'],
            'member' => $activity_log['member'],
            'group' => $activity_log['group'],
            'domain' => $activity_log['domain'],
            'created_at' => $created_at,
        ]);

        foreach ($activity_logs as $activity_log) {
            $response->seeJson([
                'id' => (string) $activity_log['id'],
                'company' => (string) $activity_log['company'],
            ]);
        }
    }

    public function testActivityLogsListPaginate(): void
    {
        $this->login();

        $response = $this->graphQL('
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
