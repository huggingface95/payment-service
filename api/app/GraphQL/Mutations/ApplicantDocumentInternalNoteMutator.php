<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantDocumentInternalNote;

class ApplicantDocumentInternalNoteMutator
{
    /**
     * @param  $root
     * @param  array  $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $user = auth()->user();
        $args['member_id'] = $user->id;

        return ApplicantDocumentInternalNote::create($args);
    }
}
