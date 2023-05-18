<?php

namespace Feature\GraphQL\Mutations;

use App\Models\ApplicantIndividualNotes;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApplicantIndividualNotesMutationTest extends TestCase
{
    /**
     * ApllicantIndividualNotes Mutation Testing
     *
     * @return void
     */
    public function testCreateApplicantIndividualNoteNoAuth(): void
    {
        $seq = DB::table('applicant_individual_notes')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_individual_notes_id_seq RESTART WITH '.$seq);

        $this->graphQL('
            mutation CreateApplicantIndividualNote(
                  $note: String!
                  $applicant_individual_id: ID!
                  $member_id: ID!
            ) {
                  createApplicantIndividualNote(
                    note: $note
                    applicant_individual_id: $applicant_individual_id
                    member_id: $member_id
                  ) {
                    id
                    note
                  }
            }
        ', [
            'note' => 'New Note by Test',
            'applicant_individual_id' => 1,
            'member_id' => 2,
        ])->seeJsonContains([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testCreateApplicantIndividualNote(): void
    {
        $seq = DB::table('applicant_individual_notes')
                ->max('id') + 1;

        DB::select('ALTER SEQUENCE applicant_individual_notes_id_seq RESTART WITH '.$seq);

        $this->postGraphQL(['query' => 'mutation CreateApplicantIndividualNote(
                  $note: String!
                  $applicant_individual_id: ID!
                  $member_id: ID!
            ) {
                  createApplicantIndividualNote(
                    note: $note
                    applicant_individual_id: $applicant_individual_id
                    member_id: $member_id
                  ) {
                    id
                    note
                  }
            }',
            'variables' => [
                'note' => 'New Note by Test',
                'applicant_individual_id' => 1,
                'member_id' => 2,
            ],
        ],
        [
            'Authorization' => 'Bearer '.$this->login(),
        ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['createApplicantIndividualNote']['id'],
                'note' => $id['data']['createApplicantIndividualNote']['note'],
            ],
        ]);
    }

    public function testDeleteApplicantIndividualNote(): void
    {
        $applicantNote = ApplicantIndividualNotes::orderBy('id', 'DESC')->first();

        $this->postGraphQL(['query' => 'mutation DeleteApplicantIndividualNote(
                  $id: ID!
            ) {
                  deleteApplicantIndividualNote(
                    id: $id
                  ) {
                    id
                    note
                  }
            }',
            'variables' => [
                'id' => $applicantNote->id,
            ],
        ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]);

        $id = json_decode($this->response->getContent(), true);

        $this->seeJsonContains([
            [
                'id' => $id['data']['deleteApplicantIndividualNote']['id'],
                'note' => $id['data']['deleteApplicantIndividualNote']['note'],
            ],
        ]);
    }
}
