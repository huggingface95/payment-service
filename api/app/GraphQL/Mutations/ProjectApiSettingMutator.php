<?php

namespace App\GraphQL\Mutations;

use App\Models\Project;

class ProjectApiSettingMutator extends BaseMutator
{

    public function update($root, array $args): \Illuminate\Database\Eloquent\Collection
    {
        /** @var Project $project */
        $project = Project::query()->find($args['project_id']);
        foreach ($args['input'] as $setting) {
            $project->projectApiSettings()
                ->where('provider_id', $setting['provider_id'])
                ->where('provider_type', $setting['provider_type'])
                ->update($setting);
        }

        return $project->projectApiSettings()->get();
    }
}
