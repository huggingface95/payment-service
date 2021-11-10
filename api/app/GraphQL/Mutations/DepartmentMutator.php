<?php

namespace App\GraphQL\Mutations;

use App\Models\DepartmentPosition;
use App\Models\Departments;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DepartmentMutator extends BaseMutator
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
            $department = Departments::find($args['id']);
            if (isset($args['department_positions_id'])) {
                DepartmentPosition::whereIn('id',$args['department_positions_id'])->update(['department_id' => $args['id']]);
            }

            return $department;
    }

}
