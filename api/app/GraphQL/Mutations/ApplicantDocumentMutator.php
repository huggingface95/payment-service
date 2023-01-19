<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantDocument;

class ApplicantDocumentMutator
{
    public function attachTag($root, array $args): ApplicantDocument
    {
        $note = ApplicantDocument::find($args['applicant_document_id']);
        if (!$note) {
            throw new GraphqlException('Applicant document not found', 'not found', 404);
        }

        $note->tags()->detach();
        $note->tags()->attach($args['applicant_document_tag_id']);

        return $note;
    }

    public function detachTag($root, array $args): ApplicantDocument
    {
        $note = ApplicantDocument::find($args['applicant_document_id']);
        if (!$note) {
            throw new GraphqlException('Applicant document not found', 'not found', 404);
        }

        $note->tags()->detach($args['applicant_document_tag_id']);

        return $note;
    }
}
