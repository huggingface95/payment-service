<?php

namespace Feature\GraphQL\Queries;

use App\Models\ApplicantIndividual;
use App\Models\ApplicantIndividualNotes;
use Tests\TestCase;

class ApplicantIndividualNotesQueryTest extends TestCase
{
    public function testQueryApplicantIndividualNotesNoAuth(): void
    {
        $this->graphQL('
            {
                applicantIndividualNotes {
                    data {
                        id
                        note
                    }
                }
            }
        ')->seeJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function testQueryApplicantIndividualNote(): void
    {
        $applicantNote = ApplicantIndividualNotes::orderBy('id', 'ASC')
            ->first();

        $this->postGraphQL(
            [
                'query' => '
                query ApplicantIndividualNote($id: ID) {
                    applicantIndividualNote(id: $id) {
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
            ]
        )->seeJson([
            'data' => [
                'applicantIndividualNote' => [
                    'id' => (string) $applicantNote->id,
                    'note' => (string) $applicantNote->note,
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualNotes(): void
    {
        $applicantNotes = ApplicantIndividualNotes::get();

        foreach ($applicantNotes as $applicantNote) {
            $data[] = [
                'id' => (string) $applicantNote->id,
                'note' => (string) $applicantNote->note,
            ];
        }

        $this->postGraphQL(
            [
                'query' => '
                {
                    applicantIndividualNotes {
                        data {
                            id
                            note
                        }
                    }
                }',
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividualNotes' => [
                    'data' => $data,
                ],
            ],
        ]);
    }

    public function testQueryApplicantIndividualNotesWithApplicantId(): void
    {
        $applicant = ApplicantIndividual::find(ApplicantIndividualNotes::select('applicant_individual_id')->first());

        $applicantNotes = ApplicantIndividualNotes::where('applicant_individual_id', $applicant[0]->id)->get();

        foreach ($applicantNotes as $applicantNote) {
            $data[] = [
                'id' => (string) $applicantNote->id,
                'note' => (string) $applicantNote->note,
            ];
        }

        $this->postGraphQL(
            [
                'query' => 'query ApplicantIndividualNotes($id: ID) {
                    applicantIndividualNotes (
                        applicant_individual_id: $id
                    ) {
                        data {
                            id
                            note
                        }
                    }
                }',
                'variables' => [
                    'id' => (string) $applicant[0]->id,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->login(),
            ]
        )->seeJson([
            'data' => [
                'applicantIndividualNotes' => [
                    'data' => $data,
                ],
            ],
        ]);
    }
}
