<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Company;
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
        $company = Company::find($args['id']);
        if (isset($args['additional_fields_info'])) {
            $args['additional_fields_info'] = $this->setAdditionalField($args['additional_fields_info']);
        }
        if (isset($args['additional_fields_basic'])) {
            $args['additional_fields_basic'] = $this->setAdditionalField($args['additional_fields_basic']);
        }
        if (isset($args['additional_fields_settings'])) {
            $args['additional_fields_settings'] = $this->setAdditionalField($args['additional_fields_settings']);
        }
        if (isset($args['additional_fields_data'])) {
            $args['additional_fields_data'] = $this->setAdditionalField($args['additional_fields_data']);
        }
        $company->update($args);

        return $company;
    }

    public function delete($root, array $args, GraphQLContext $context)
    {
        $company = Company::find($args['id']);

        $company->delete();

        return $company;
    }
}
