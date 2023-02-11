<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphqlException;
use App\Models\Project;

class ProjectApiSettingMutator extends BaseMutator
{

    /**
     * @param    $root
     * @param array $args
     * @return mixed
     * @throws GraphqlException
     */
    public function update($root, array $args)
    {
        /** @var Project $project */
        $project = Project::find($args['project_id']);
        $project->projectApiSettings()->delete();
        foreach ($args['input'] as $setting){
            $project->projectApiSettings()->create($setting);
        }

        return $project->projectApiSettings()->get();
    }
}
