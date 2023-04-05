<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\Clickhouse\AuthenticationLog;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthenticationLogsQueryTest extends TestCase
{
    public function testAuthenticationLogsNoAuth(): void
    {
        $this->graphQL('
            {
                authenticationLogs {
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

    public function testAuthenticationLogsList(): void
    {
        $authentication_logs = DB::connection('clickhouse')
            ->table((new AuthenticationLog())->getTable())
            ->select(['id', 'company'])
            ->limit(10)
            ->orderBy('id', 'DESC')
            ->get();

        $response = $this->postGraphQL(
            [
                'query' => '
            {
                authenticationLogs {
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

        foreach ($authentication_logs as $authentication_log) {
            $response->seeJson([
                'id' => (string) $authentication_log['id'],
                'company' => (string) $authentication_log['company'],
            ]);
        }
    }

    public function testAuthenticationLogsListWithQuery(): void
    {
        $authentication_log = DB::connection('clickhouse')
            ->table((new AuthenticationLog())->getTable())
            ->limit(1)
            ->first();

        $authentication_logs = DB::connection('clickhouse')
            ->table((new AuthenticationLog())->getTable())
            ->where('company', $authentication_log['company'])
            ->where('email', $authentication_log['email'])
            ->where('provider', $authentication_log['provider'])
            ->where('domain', $authentication_log['domain'])
            ->where('ip', $authentication_log['ip'])
            ->where('expired_at', $authentication_log['expired_at'])
            ->where('created_at', $authentication_log['created_at'])
            ->get();

        $expired_at = substr($authentication_log['expired_at'], 0, 10);
        $created_at = substr($authentication_log['created_at'], 0, 10);

        $response = $this->postGraphQL(
            [
                'query' => '
                query(
                    $company: String!, $email: String!, $provider: String!, $domain: String!,
                    $ip: String!, $country: String!, $city: String!, $platform: String!,
                    $browser: String!, $device_type: String!, $model: String!, $status: String!,
                ) {
                    authenticationLogs(
                        query: {
                            company: $company
                            email: $email
                            provider: $provider
                            domain: $domain
                            ip: $ip
                            country: $country
                            city: $city
                            platform: $platform
                            browser: $browser
                            device_type: $device_type
                            model: $model
                            status: $status
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
                    'company' => $authentication_log['company'],
                    'email' => $authentication_log['email'],
                    'provider' => $authentication_log['provider'],
                    'domain' => $authentication_log['domain'],
                    'ip' => $authentication_log['ip'],
                    'country' => $authentication_log['country'],
                    'city' => $authentication_log['city'],
                    'platform' => $authentication_log['platform'],
                    'browser' => $authentication_log['browser'],
                    'device_type' => $authentication_log['device_type'],
                    'model' => $authentication_log['model'],
                    'status' => $authentication_log['status'],
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

        foreach ($authentication_logs as $authentication_log) {
            $response->seeJson([
                'id' => (string) $authentication_log['id'],
                'company' => (string) $authentication_log['company'],
            ]);
        }
    }

    public function testAuthenticationLogsListPaginate(): void
    {
        $response = $this->postGraphQL(
            [
                'query' => '
        {
            authenticationLogs(page: 1, first: 3) {
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

        $response->seeJson([
            'count' => 3,
            'currentPage' => 1,
            'firstItem' => 1,
            'hasMorePages' => true,
            'lastItem' => 3,
        ]);
    }
}
