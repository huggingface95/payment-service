<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\DepartmentPosition;
use App\Models\Departments;


class DepartmentPositionMutator extends BaseMutator
{


    /**
     * Update position
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function create($root, array $args)
    {

        if ($args['department_id']) {
            $department = Departments::find($args[$args['department_id']]);
            unset($args['department_id']);
            if (!$department) {
                throw new GraphqlException('Department not found',"not found", 404);
            }
            $position = DepartmentPosition::create($args);
            $department->attach($position->id);
        } else {
            $position = DepartmentPosition::create($args);
        }

        return $position;
    }

    /**
     * Update position
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
            $position->update($args);

            return $position;
    }


}
