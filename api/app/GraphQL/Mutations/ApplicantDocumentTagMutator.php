<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantDocument;

class ApplicantDocumentTagMutator extends BaseMutator
{
    public function attach($root, array $args): ApplicantDocument
    {
        $document = ApplicantDocument::find($args['applicant_document_id']);
        if (!$document) {
            throw new GraphqlException('Applicant document not found', 'not found', 404);
        }

        $document->tags()->detach();
        $document->tags()->attach($args['applicant_document_tag_id']);

        return $document;
    }

    public function detach($root, array $args): ApplicantDocument
    {
        $document = ApplicantDocument::find($args['applicant_document_id']);
        if (!$document) {
            throw new GraphqlException('Applicant document not found', 'not found', 404);
        }

        $document->tags()->detach($args['applicant_document_tag_id']);

        return $document;
    }
}
