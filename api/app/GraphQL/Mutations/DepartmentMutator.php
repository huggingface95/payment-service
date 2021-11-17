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
                $positions = DepartmentPosition::whereIn('id',$args['active_department_positions_id'])->get();
                $departmentPositionIds = DepartmentPosition::getPositionsIdByDepartment($args['id']);
                $activePositionIds = [];
                foreach ($positions as $position) {
                    if (!$position->is_active  && !$position->members->isEmpty()) {
                        throw new GraphqlException('Department positions are already in use',"used");
                    }

                    $activePositionIds[] = $position->id;
                }
                DepartmentPosition::whereIn('id',$departmentPositionIds)->update(['department_id' => $args['id'], 'is_active'=>false]);
                DepartmentPosition::whereIn('id',$activePositionIds)->update(['department_id' => $args['id'], 'is_active'=>true]);

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
