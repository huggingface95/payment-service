<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantDocumentInternalNote;

class ApplicantDocumentInternalNoteMutator
{
    /**
     * @param    $root
     * @param  array  $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $user = auth()->user();
        $args['member_id'] = $user->id;

        return ApplicantDocumentInternalNote::create($args);
    }

    public function attachTag($root, array $args): ApplicantDocumentInternalNote
    {
        $note = ApplicantDocumentInternalNote::find($args['applicant_document_internal_note_id']);
        if (! $note) {
            throw new GraphqlException('Applicant document internal note not found', 'not found', 404);
        }

        $note->tags()->detach();
        $note->tags()->attach($args['applicant_document_tag_id']);

        return $note;
    }

    public function detachTag($root, array $args): ApplicantDocumentInternalNote
    {
        $note = ApplicantDocumentInternalNote::find($args['applicant_document_internal_note_id']);
        if (! $note) {
            throw new GraphqlException('Applicant document internal note not found', 'not found', 404);
        }

        $note->tags()->detach($args['applicant_document_tag_id']);

        return $note;
    }
}
