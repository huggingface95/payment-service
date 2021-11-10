<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\UpdateException;
use App\Models\Companies;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CompanyMutator extends BaseMutator
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
            $company = Companies::find($args['id']);
            if (isset($args['additional_fields'])) {
                $args['additional_fields']  = $this->setAdditionalField($args['additional_fields']);
            }
            $company->update($args);
            return $company;
    }

}
