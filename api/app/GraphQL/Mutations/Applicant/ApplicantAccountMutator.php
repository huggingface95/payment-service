<?php

namespace App\GraphQL\Mutations\Applicant;

use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\Account;

class ApplicantAccountMutator extends BaseMutator
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @return mixed
     */
    public function update($root, array $args)
    {
        $applicant = auth()->user();

        $account = Account::where('id', $args['id'])->where('owner_id', $applicant->id)->first();
        if (! $account) {
            throw new GraphqlException('Applicant account not found', 'use');
        }

        $account->update([
            'is_show' => $args['is_show'],
        ]);

        return $account;
    }
}
