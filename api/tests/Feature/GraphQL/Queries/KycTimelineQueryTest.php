<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\KycTimeline;
use Database\Seeders\ApplicantDocumentTableSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DocumentStateTableSeeder;
use Database\Seeders\DocumentTypeTableSeeder;
use Database\Seeders\FileTableSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KycTimelineQueryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->call(DocumentStateTableSeeder::class);
        (new DatabaseSeeder())->call(DocumentTypeTableSeeder::class);
        (new DatabaseSeeder())->call(FileTableSeeder::class);
        (new DatabaseSeeder())->call(ApplicantDocumentTableSeeder::class);
    }

    public function testQueryKycTimeLineNoAuth(): void
    {
        $kycTimeLine = DB::connection('pgsql_test')
            ->table('kyc_timeline')
            ->orderBy('id', 'ASC')
            ->first();

        $this->graphQL('
            query KycTimeLines(
              $applicant_id: ID!
              $applicant_type: ApplicantType!
              $company_id: ID!
            ) {
              kycTimelines(
                applicant_id: $applicant_id
                applicant_type: $applicant_type
                company_id: $company_id
              ) {
                data {
                  os
                  browser
                  ip
                  action
                  action_type
                  applicant_id
                  applicant_type
                  tag
                }
              }
            }
        ',
        [
            'applicant_id' => $kycTimeLine->applicant_id,
            'applicant_type' => $kycTimeLine->applicant_type,
            'company_id' => $kycTimeLine->company_id,
        ])->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryKycTimelinesWithFilterByCondition(): void
    {
        $document = DB::connection('pgsql_test')
            ->table('kyc_timeline')
            ->orderBy('id', 'ASC')
            ->first();

        $expect  = [
            'os' => (string) $document->os,
            'browser' => (string) $document->browser,
            'ip' => (string) $document->ip,
            'action' => (string) $document->action,
            'action_type' => (string) strtoupper($document->action_type),
            'applicant_id' => (string) $document->applicant_id,
            'applicant_type' => (string) $document->applicant_type,
            'tag' => (string) $document->tag,
        ];

        $this->postGraphQL(
            [
                'query' => '
                query KycTimelines(
                    $applicant_id: ID!
                    $applicant_type: ApplicantType!
                    $company_id: ID!
                ) {
                    kycTimelines (
                        applicant_id: $applicant_id,
                        applicant_type: $applicant_type,
                        company_id: $company_id
                    ) {
                        data {
                            os
                            browser
                            ip
                            action
                            action_type
                            applicant_id
                            applicant_type
                            tag
                        }
                    }
                }',
                'variables' => [
                    'applicant_id' => $document->applicant_id,
                    'applicant_type' => $document->applicant_type,
                    'company_id' => $document->company_id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJsonContains($expect);
    }
}
