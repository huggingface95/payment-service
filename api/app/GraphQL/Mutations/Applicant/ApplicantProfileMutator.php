<?php

namespace App\GraphQL\Mutations\Applicant;

use App\Enums\ClientTypeEnum;
use App\Exceptions\GraphqlException;
use App\GraphQL\Mutations\BaseMutator;
use App\Models\Files;

class ApplicantProfileMutator extends BaseMutator
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

        if (! empty($args['photo_id'])) {
            $file = Files::where('id', $args['photo_id'])
                ->where('entity_type', ClientTypeEnum::APPLICANT->toString())
                ->first();

            if (! $file) {
                throw new GraphqlException('The photo is not associated with the Applicant', 'use');
            }
        }

        $applicant->update($args);

        return $applicant;
    }
}
