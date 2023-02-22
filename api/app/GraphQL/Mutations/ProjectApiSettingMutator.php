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
        foreach ($args['input'] as $setting){
            $project->projectApiSettings()
                ->where('provider_id', $setting['provider_id'])
                ->where('provider_type', $setting['provider_type'])
                ->update($setting);
        }

        return $project->projectApiSettings()->get();
    }
}
