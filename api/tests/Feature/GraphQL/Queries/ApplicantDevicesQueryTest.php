<?php

namespace Feature\GraphQL\Queries;

use App\Models\Clickhouse\ActiveSession;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApplicantDevicesQueryTest extends TestCase
{
    public function testApplicantDevicesNoAuth(): void
    {
        $this->graphQL('
            {
              applicantDevices  {
                id
                ip
                platform
                browser
                device_type
                model
                trusted
              }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testApplicantDevicesList(): void
    {
        $this->markTestSkipped('Skipped');
        $active_sessions = DB::connection('clickhouse_test')
            ->table((new ActiveSession())->getTable())
            ->select(['id', 'device_type'])
            ->where('email', 'applicant1@test.com')
            ->orderBy('created_at', 'DESC')
            ->get();

        $response = $this->postGraphQL(
            [
                'query' => '{
                      applicantDevices  {
                        id
                        device_type
                      }
                    }
        ', ],
            [
                'Authorization' => 'Bearer ' . $this->login(['email' => 'applicant1@test.com', 'password' => '1234567Qa', 'client_type' => 'applicant']),
            ]
        );

        foreach ($active_sessions as $session) {
            $response->seeJsonContains([
                'id' => (string) $session['id'],
                'device_type' => (string) $session['device_type'],
            ]);
        }
    }
}
