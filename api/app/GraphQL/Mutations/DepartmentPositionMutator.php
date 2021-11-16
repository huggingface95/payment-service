<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\DepartmentPosition;


class DepartmentPositionMutator extends BaseMutator
{


    /**
     * Update department
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function update($root, array $args)
    {
            $position = DepartmentPosition::find($args['id']);
            if (!$position->is_active && !$position->members->isEmpty() ) {
                throw new GraphqlException('Department positions are already in use',"used");
            }
            $position->update();

            return $position;
    }

}
