<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantBankingAccess;

class ApplicantBankingAccessMutator
{
    /**
     * @param  $root
     * @param  array  $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $memberId = auth()->user()->id;
        $args['member_id'] = $memberId;

        $applicantBankingAccess = ApplicantBankingAccess::create($args);

        return $applicantBankingAccess;
    }

    /**
     * @param  $root
     * @param  array  $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        $memberId = auth()->user()->id;
        $args['member_id'] = $memberId;

        $applicantBankingAccess = ApplicantBankingAccess::findOrFail($args['id']);
        $applicantBankingAccess->update($args);

        return $applicantBankingAccess;
    }
}
