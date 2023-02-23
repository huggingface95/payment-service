<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\ApplicantDocumentRejectDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicantDocumentRejectDetailMutator extends BaseMutator
{
    public function create($root, array $args): ApplicantDocumentRejectDetail
    {
        $memberId = Auth::user()->id;

        $rejectDetail = DB::transaction(function () use ($args, $memberId) {
            $rejectDetail = ApplicantDocumentRejectDetail::create([
                'applicant_document_id' => $args['applicant_document_id'],
                'member_id' => $memberId,
            ]);

            if (isset($args['applicant_document_tag_id'])) {
                foreach ($args['applicant_document_tag_id'] as $tag) {
                    $rejectDetail->applicantDocumentTags()->attach($tag);
                }
            }

            return $rejectDetail;
        });

        return $rejectDetail;
    }

    public function deleteTag($root, array $args): ApplicantDocumentRejectDetail|GraphqlException
    {
        $rejectDetail = ApplicantDocumentRejectDetail::find($args['id']);
        if (! $rejectDetail) {
            return new GraphqlException('Applicant document reject details not found', 'not found', 404);
        }

        if (isset($args['applicant_document_tag_id'])) {
            foreach ($args['applicant_document_tag_id'] as $tag) {
                $rejectDetail->applicantDocumentTags()->detach($tag);
            }
        }

        if ($rejectDetail->applicantDocumentTags()->count() === 0) {
            $rejectDetail->delete();
        }

        return $rejectDetail;
    }
}
