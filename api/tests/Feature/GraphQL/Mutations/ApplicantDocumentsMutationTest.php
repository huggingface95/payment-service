<?php

namespace Tests\Feature\GraphQL\Mutations;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApplicantDocumentsMutationTest extends TestCase
{
    public function testCreateApplicantDocumentNoAuth(): void
    {
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
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateApplicantDocument(): void
    {
        $seq = DB::table('applicant_documents')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_documents_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(
            [
                'query' => '
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
               }',
                'variables' => [
                    'company_id' => 1,
                    'applicant_id' => 1,
                    'applicant_type' => 'ApplicantIndividual',
                    'document_type_id' => 1,
                    'document_state_id' => 1,
                    'file_id' => 1,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        );

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
