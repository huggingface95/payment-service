<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\KycTimeline;
use Database\Seeders\ApplicantDocumentTableSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DocumentStateTableSeeder;
use Database\Seeders\DocumentTypeTableSeeder;
use Database\Seeders\FileTableSeeder;
use Database\Seeders\KycTimelineTableSeeder;
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
        $this->graphQL('
            {
                kycTimelines {
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
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    /**
     * @dataProvider provide_testQueryKycTimelinesWithFilterByCondition
     */
    public function testQueryKycTimelinesWithFilterByCondition($cond, $value): void
    {
        $documents = KycTimeline::where($cond, $value)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($documents as $document) {
            $expect['data']['kycTimelines']['data'][] = [
                'os' => (string) $document->os,
                'browser' => (string) $document->browser,
                'ip' => (string) $document->ip,
                'action' => (string) $document->action,
                'action_type' => (string) strtoupper($document->action_type),
                'applicant_id' => (string) $document->applicant_id,
                'applicant_type' => (string) $document->applicant_type,
                'tag' => (string) $document->tag,
            ];
        }

        $this->postGraphQL([
            'query' => '
                query KycTimelines($id: Mixed) {
                    kycTimelines (
                        filter: { column: ' . strtoupper($cond) . ', operator: EQ, value: $id }
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
                'id' => $value,
            ]
        ],
        [
            "Authorization" => "Bearer " . $this->login()
        ])->seeJson($expect);
    }

    public function provide_testQueryKycTimelinesWithFilterByCondition()
    {
        return [
            ['applicant_id', '1'],
            ['company_id', '1'],
            ['applicant_type', 'ApplicantIndividual'],
            ['applicant_type', 'ApplicantCompany'],
        ];
    }

}
