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
            if (isset($args['active_department_positions_id'])) {
                $checkPositions = DepartmentPosition::whereIn('id',$args['active_department_positions_id'])->where('department_id',$department->id)->get();
                if ($checkPositions->isEmpty()) {
                    throw new GraphqlException('Position is not be use this department',"internal");
                }
                foreach ($department->positions as $position) {
                    if (!$position->is_active  && !$position->members->isEmpty()) {
                        throw new GraphqlException('Department positions are already in use',"used");
                    }
                    if (in_array($position->id,$args['active_department_positions_id'])) {
                        $position->is_active = true;
                    } else {
                        $position->is_active = false;
                    }
                    $position->update();

                }
            }

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
                throw new GraphqlException('Entity not found',"use",404);
            }
            $department->delete();
            return $department;
        } catch (\Exception $exception)
        {
            throw new GraphqlException('Department positions are already in use',"use");
        }

    }

}
