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
        (new DatabaseSeeder())->call(KycTimelineTableSeeder::class);
    }

    /**
     * @dataProvider provide_testQueryKycTimelinesWithFilterByCondition
     */
    public function testQueryKycTimelinesWithFilterByCondition($cond, $value): void
    {
        $this->login();

        $documents = KycTimeline::where($cond, $value)->orderBy('id', 'ASC')->get();

        $expect = [];

        foreach ($documents as $document) {
            $expect['data']['kycTimelines']['data'][] = [
                'os' => (string) $document->os,
                'browser' => (string) $document->browser,
                'ip' => (string) $document->ip,
                'action' => (string) $document->action,
                'action_state' => (string) $document->action_state,
                'action_type' => (string) $document->action_type,
                'applicant_id' => (string) $document->applicant_id,
                'applicant_type' => (string) $document->applicant_type,
            ];
        }

        $this->graphQL('
            query KycTimelines($id: Mixed) {
                kycTimelines (
                    filter: { column: ' . strtoupper($cond) . ', operator: EQ, value: $id }
                ) {
                    data {
                        os
                        browser
                        ip
                        action
                        action_state
                        action_type
                        applicant_id
                        applicant_type
                    }
                }
            }
        ', [
            'id' => $value,
        ])->seeJson($expect);
    }

    public function provide_testQueryKycTimelinesWithFilterByCondition()
    {
        return [
            ['applicant_id', '1'],
            ['company_id', '1'],
            ['company_id', '2'],
            ['applicant_type', 'ApplicantIndividual'],
            ['applicant_type', 'ApplicantCompany'],
        ];
    }

}
