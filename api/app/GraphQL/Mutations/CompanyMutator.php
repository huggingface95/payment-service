<?php

namespace App\GraphQL\Mutations;

use App\Models\Companies;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Exception\InvalidArgument;

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

    public function update($root, array $args, GraphQLContext $context)
    {
        $maxLength = env('MAX_LENGTH_STRING',200);
        $company = Companies::find($args['id']);
        if (isset($args['additional_fields'])) {

            $fields = [];
            foreach ($args['additional_fields']  as $additionalField) {
                if (strlen($additionalField['field_value']) > $maxLength) {
                    throw new InvalidArgument("Max length field is ". $maxLength);
                }
                if ($additionalField['field_type'] === "Text" ) {
                    $additionalField['field_value'] = filter_var($additionalField['field_value'],FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
                }
                if ($additionalField['field_type'] === "TextArea"){
                    $additionalField['field_value'] = filter_var($additionalField['field_value'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }
                $fields[] = $additionalField;
            }
            $args['additional_fields'] = $fields;
        }
        $company->update($args);
        return $company;
    }

}
