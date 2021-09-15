<?php

namespace App\GraphQL\Mutations;

use App\Models\Companies;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CompanyMutator
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

    public function update($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ($args['additional_fields']) {
            Companies::where(['id'=>$args['id']])->update(['additional_fields'=>$args['additional_fields']]);
        }
        return $args;
    }

}
