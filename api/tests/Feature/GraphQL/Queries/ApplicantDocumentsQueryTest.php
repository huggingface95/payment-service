<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\ApplicantDocument;
use App\Models\ApplicantIndividual;
use Database\Seeders\ApplicantDocumentTableSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DocumentStateTableSeeder;
use Database\Seeders\DocumentTypeTableSeeder;
use Database\Seeders\FileTableSeeder;
use Tests\TestCase;

class ApplicantDocumentsQueryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->call(DocumentStateTableSeeder::class);
        (new DatabaseSeeder())->call(DocumentTypeTableSeeder::class);
        (new DatabaseSeeder())->call(FileTableSeeder::class);
        (new DatabaseSeeder())->call(ApplicantDocumentTableSeeder::class);
    }

    /**
     * @dataProvider provide_testQueryApplicantDocumentsWithFilterByCondition
     */
    public function testQueryApplicantDocumentsWithFilterByCondition($cond, $value): void
    {
        $this->login();

        $documents = ApplicantDocument::where($cond, $value)->orderBy('id', 'ASC')->get();
        
        $expect = [];

        foreach ($documents as $document) {
            $expect['data']['applicantDocuments']['data'][] = [
                'document_type_id' => (string) $document->document_type_id,
                'document_state_id' => (string) $document->document_state_id,
                'applicant_id' => (string) $document->applicant_id,
                'applicant_type' => (string) $document->applicant_type,
            ];
        }

        $this->graphQL('
            query ApplicantDocuments($id: Mixed) {
                applicantDocuments (
                    filter: { column: ' . strtoupper($cond) . ', operator: EQ, value: $id }
                ) {
                    data {
                        document_type_id
                        document_state_id
                        applicant_id
                        applicant_type
                    }
                }
            }
        ', [
            'id' => $value,
        ])->seeJson($expect);
    }

    public function provide_testQueryApplicantDocumentsWithFilterByCondition()
    {
        return [
            ['id', '1'],
            ['applicant_id', '1'],
            ['document_type_id', '1'],
            ['document_type_id', '2'],
            ['document_state_id', '1'],
            ['document_state_id', '2'],
        ];
    }

}
