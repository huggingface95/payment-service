<?php

namespace App\GraphQL\Mutations;

use App\Models\ApplicantIndividual;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;


class ApplicantMutator extends BaseMutator
{
    /**
     * Return a value for the field.
     *
     * @param  @param  null  $root Always null, since this field has no parent.
     * @param  array<string, mixed>  $args The field arguments passed by the client.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Shared between all fields.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */

    public function update($root, array $args, GraphQLContext $context)
    {
        $applicant = ApplicantIndividual::find($args['id']);
        if (isset($args['additional_fields'])) {
            $additionalFields = $args['additional_fields'];
            $args['additional_fields']  = $this->setAdditionalField($additionalFields);
        }
        if (isset($args['contacts_additional_fields'])) {
            $contactAdditionalFields = $args['contacts_additional_fields'];
            $args['contacts_additional_fields']  = $this->setAdditionalField($contactAdditionalFields);
        }
        $applicant->update($args);
        return $applicant;
    }

}
