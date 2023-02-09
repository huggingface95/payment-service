<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Department;

class DepartmentMutator extends BaseMutator
{
    /**
     * Create department
     *
     * @param    $root
     * @param  array  $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $department = Department::create($args);

        if (isset($args['department_positions_id'])) {
            if (! $department->positions->isEmpty()) {
                $currentPosition = collect($department->positions)->pluck('id')->all();
                $positionsActive = array_diff($args['department_positions_id'], $currentPosition);
            } else {
                $positionsActive = $args['department_positions_id'];
            }
            $department->positions()->attach($positionsActive);
        }

        return $department;
    }

    /**
     * Update department
     *
     * @param    $root
     * @param  array  $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        $department = Department::find($args['id']);
        if (! $department) {
            throw new GraphqlException('An entry with this id does not exist', 'not found', 404);
        }
        if (isset($args['department_positions_id'])) {
            $department->positions()->sync($args['department_positions_id'], true);

            unset($args['department_positions_id']);
        }
        $department->update($args);

        return $department;
    }

    /**
     * Delete department
     *
     * @param    $root
     * @param  array  $args
     * @return mixed
     */
    public function delete($root, array $args)
    {
        try {
            $department = Department::find($args['id']);
            if (! $department) {
                throw new GraphqlException('An entry with this id does not exist', 'not found', 404);
            }
            $department->delete();

            return $department;
        } catch (\Exception $exception) {
            throw new GraphqlException('Department positions are already in use', 'use');
        }
    }
}
