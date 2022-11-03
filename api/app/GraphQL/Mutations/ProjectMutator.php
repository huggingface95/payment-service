<?php

namespace App\GraphQL\Mutations;

use App\Models\Project;

class ProjectMutator extends BaseMutator
{
    /**
     * @param  $root
     * @param  array  $args
     * @return mixed
     */
    public function create($root, array $args)
    {
        $project = Project::create($args);

        if (isset($args['project_settings'])) {
            foreach ($args['project_settings'] as $settings) {
                $project->projectSettings()->create($settings);
            }
        }

        return $project;
    }

    /**
     * @param  $root
     * @param  array  $args
     * @return mixed
     */
    public function update($root, array $args)
    {
        $project = Project::find($args['id']);

        $project->update($args);

        if (isset($args['project_settings'])) {
            $project->projectSettings()->delete();

            foreach ($args['project_settings'] as $settings) {
                $project->projectSettings()->create($settings);
            }
        }

        return $project;
    }
}
