<?php

namespace Tests\Feature\GraphQL\Mutations;

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DocumentStateTableSeeder;
use Database\Seeders\DocumentTypeTableSeeder;
use Database\Seeders\FileTableSeeder;
use Tests\TestCase;

class ApplicantDocumentsMutationTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->call(DocumentStateTableSeeder::class);
        (new DatabaseSeeder())->call(DocumentTypeTableSeeder::class);
        (new DatabaseSeeder())->call(FileTableSeeder::class);
    }

    public function testCreateProject(): void
    {
        $this->login();

        $this->graphQL('
            mutation CreateApplicantDocument(
                $company_id: ID!
                $applicant_id: ID!
                $applicant_type: ApplicantType!
                $document_type_id: ID!
                $document_state_id: ID!
                $file_id: ID!
            ) {
                createApplicantDocument(
                    company_id: $company_id
                    applicant_id: $applicant_id
                    applicant_type: $applicant_type
                    document_type_id: $document_type_id
                    document_state_id: $document_state_id
                    file_id: $file_id
                )
                {
                    id
                    document_type_id
                    document_state_id
                }
           }
        ', [
            'company_id' => 1,
            'applicant_id' => 1,
            'applicant_type' => 'ApplicantIndividual',
            'document_type_id' => 1,
            'document_state_id' => 1,
            'file_id' => 1,
        ]);
        
        $response = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            'data' => [
                'createApplicantDocument' => [
                    'id' => $response['data']['createApplicantDocument']['id'],
                    'document_type_id' => '1',
                    'document_state_id' => '1',
                ],
            ],
        ]);
    }

}
