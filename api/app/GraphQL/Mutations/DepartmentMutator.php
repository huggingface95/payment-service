<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\DepartmentPosition;
use App\Models\Departments;


class DepartmentMutator extends BaseMutator
{

    /**
     * Create department
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $department = Departments::create($args);

        if (isset($args['department_positions_name'])) {
            foreach ($args['department_positions_name'] as $position) {
                DepartmentPosition::create([
                    'name'=> $position,
                    'department_id' => $department->id
                ]);
            }

        }

        return $department;
    }

    /**
     * Update department
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function update($root, array $args)
    {
            $department = Departments::find($args['id']);
            if (!$department) {
                throw new GraphqlException('An entry with this id does not exist',"not found",404);
            }
            if (isset($args['active_department_positions_id'])) {
                if (!$department->positions->isEmpty()) {
                    $currentPosition =collect($department->positions)->pluck('id')->all();
                    $positionsActive = array_diff($args['active_department_positions_id'],$currentPosition);
                } else {
                    $positionsActive=$args['active_department_positions_id'];
                }
                $department->positions()->attach($positionsActive);

                unset($args['active_department_positions_id']);
            }
            $department->update($args);

            return $department;
    }

    /**
     * Delete department
     * @param $root
     * @param array $args
     * @return mixed
     */
    public function delete($root, array $args)
    {
        try {
            $department = Departments::find($args['id']);
            if (!$department) {
                throw new GraphqlException('An entry with this id does not exist',"not found",404);
            }
            $department->delete();
            return $department;
        } catch (\Exception $exception)
        {
            throw new GraphqlException('Department positions are already in use',"use");
        }

    }

}
